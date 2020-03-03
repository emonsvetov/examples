import { Component, OnInit, OnDestroy, ViewChild, ElementRef } from '@angular/core';
import { permissions } from '../../permissions';
import { _lang } from '../../pipes/lang';
import { Page } from '../../model/page';
import { Subject, Subscription } from 'rxjs';
import { MatDialog } from '@angular/material';
import { ActivatedRoute, Router } from '@angular/router';
import { TitleService } from '../../services/title.service';
import { ToastrService } from 'ngx-toastr';
import { LanguageEditorModalComponent } from '../../components/language-editor-modal/language-editor-modal.component';
import { HeaderService } from '../../services/header.service';
import { HourService } from '../../services/hour.service';
import { FormControl } from '@angular/forms';
import { takeUntil } from 'rxjs/internal/operators';
import {EditLaborHoursModalComponent} from "../../components/edit-labor-hours-modal/edit-labor-hours-modal.component";
import {ConfirmationService} from "primeng/api";


@Component({
  selector: 'app-hours',
  templateUrl: './hours.component.html',
  styleUrls: ['./hours.component.scss'],
  providers: [
    HourService,
    ConfirmationService
  ],
  preserveWhitespaces: true
})
export class HoursComponent implements OnInit, OnDestroy {
  private permissions = permissions;
  private scrollHeight: string = '50px';

  private cols: any[] = [];
  private items: any[] = [];
  private selected: any;

  private page = new Page();
  private size: number = 50;
  private totalRecords: number = 0;

  private loading: boolean = true;
  private hoursSubscription: Subscription;
  private headerSubscription: Subscription;
  private preloadSubscription: Subscription;

  private technicians: Array<object> = [];

  @ViewChild('hoursView')     hoursView: ElementRef;
  @ViewChild('actionbar')     actionbar:  ElementRef;
  @ViewChild('content')       content: ElementRef;
  @ViewChild('tableHeader')   tableHeader:  ElementRef;
  @ViewChild('tableFilter')   tableFilter:  ElementRef;
  @ViewChild('paginator')     paginator:  ElementRef;

  /** control for the selected filters */
  public technicianCtrl: FormControl = new FormControl();
  public technicianFilterCtrl: FormControl = new FormControl();
  /** Subject that emits when the component has been destroyed. */
  private _onDestroyTechnician = new Subject<void>();
  /** list of companies filtered by search keyword */
  public filteredTechnicians: any[] = [];
  /** filter object with selected filters properties */
  private filter: any = {};

  private acceptLabel: string = _lang('Yes');
  private confirmationHeader: string = _lang('DELETE CONFIRMATION');

  constructor(private hourService: HourService,
              public  dialog: MatDialog,
              private router: Router,
              private titleService: TitleService,
              private toastr: ToastrService,
              private route: ActivatedRoute,
              private confirmationService: ConfirmationService,
              private headerService: HeaderService) {
    this.page.size = this.size;
    this.page.pageNumber = 0;
    this.page.filterParams = {};
    this.page.filterParams['TECHNICIAN_DETAILS'] = true;
    this.page.filterParams['CO1_ID'] = this.headerService.CO1_ID;
    this.page.filterParams['LO1_ID'] = this.headerService.LO1_ID;

    this.headerSubscription = this.headerService.subscribe({
      next: (v) => {
        this.page.pageNumber = 0;
        this.page.filterParams['CO1_ID'] = this.headerService.CO1_ID;
        this.page.filterParams['LO1_ID'] = this.headerService.LO1_ID;
        this.getItems();
      }
    });
  }

  ngOnInit() {
    this.loading = true;
    this.titleService.setNewTitle(this.route);

    this.cols = [
      { field: 'CO1_NAME',   header: _lang('CO1_NAME'), visible: true, export: {visible: true, checked: true}, width: 120 },
      { field: 'LO1_NAME',  header: _lang('LO1_NAME'),  visible: true, export: {visible: true, checked: true}, width: 120 },
      { field: 'TE1_NAME',  header: _lang('TE1_NAME'),  visible: true, export: {visible: true, checked: true}, width: 80 },
      { field: 'HO1_DATE',  header: _lang('#Date#'),    visible: true, export: {visible: true, checked: true}, width: 120 },
      { field: 'HO1_HOURS', header: _lang('Hours'),     visible: true, export: {visible: true, checked: true}, width: 120 }
    ];

    this.hourService.preloadData().subscribe( data => {
      this.prepareData(data);
      this.getItems();
    });

    // listen for search field value changes
    this.preloadSubscription = this.technicianFilterCtrl.valueChanges
        .pipe(takeUntil(this._onDestroyTechnician))
        .subscribe(() => {
          this.filterTechnicians();
        });
  }

  ngOnDestroy() {
    if (this.hoursSubscription) {
      this.hoursSubscription.unsubscribe();
    }
    this.preloadSubscription.unsubscribe();
    this.headerSubscription.unsubscribe();
    this._onDestroyTechnician.next();
    this._onDestroyTechnician.complete();
  }

  private filterTechnicians() {
    if (!this.technicians) {
      return;
    }
    // get the search keyword
    let search = this.technicianFilterCtrl.value;
    if (!search) {
      this.filteredTechnicians = this.technicians;
      return;
    } else {
      search = search.toLowerCase();
    }
    // filter the technicians
    this.filteredTechnicians = this.technicians.filter(technician => technician['TE1_NAME'].toLowerCase().indexOf(search) > -1);
  }

  prepareData(data) {
    this.technicians = [{TE1_ID: null, TE1_NAME: _lang('#AllRecords#')}];
    let techniciansArr = data.technicians.map( technician => {
      return {
        TE1_ID:   technician.TE1_ID,
        TE1_NAME: technician.TE1_NAME
      }
    }).filter( technician => {
      return technician.TE1_NAME && technician.TE1_NAME.length > 0;
    }).sort((a, b) => {
      if (a.TE1_NAME < b.TE1_NAME)
        return -1;
      if (a.TE1_NAME > b.TE1_NAME)
        return 1;
      return 0;
    });
    this.technicians = this.technicians.concat(techniciansArr);
    this.filterTechnicians();
  }

  getScrollHeight(): number {
    return (this.hoursView.nativeElement.parentElement.parentElement.parentElement.offsetHeight) -
        (this.actionbar.nativeElement.offsetHeight + 48) - (this.tableHeader.nativeElement.offsetHeight +
        this.tableFilter.nativeElement.offsetHeight + this.paginator.nativeElement.offsetHeight);
  }

  getItems() {
    if (this.hoursSubscription) {
      this.hoursSubscription.unsubscribe();
    }

    this.loading = true;
    this.hoursSubscription = this.hourService.getHours(this.page).subscribe(res => {
        this.items = res['data'];
        this.totalRecords = res['count'];
        setTimeout(() => {this.scrollHeight = this.getScrollHeight() + 'px';});
        this.loading = false;
      },
      error => {
        this.loading = false;
        this.toastr.error(_lang('Hours') + ' ' + _lang('loading error occured'), _lang('Service Error'), {
          timeOut: 3000,
        });
    });
  }

  openLanguageEditor() {
    this.dialog.open(LanguageEditorModalComponent, {
      width: window.innerWidth - 60 + 'px',
      height: window.innerHeight - 40 + 'px',
      role: 'dialog',
      data: {
        title: _lang('Hours List'),
        componentRef: this,
        data: [],
        componentName: 'HoursComponent'
      },
    });
  }
  openEditLabor() {
    let dialogRef = this.dialog.open(EditLaborHoursModalComponent, {
      width: window.innerWidth - 60 + 'px',
      height: window.innerHeight - 40 + 'px',
      role: 'dialog',
      data: {title: _lang('Hours List'), componentRef: this, data: []},
    });
    dialogRef.beforeClose().subscribe( selected => {
        this.getItems();
    })
  }

  onPage(event) {
    this.page.size = event.rows;
    this.page.pageNumber = (event.first / event.rows);
    this.getItems();
  }

  filterChanged(col, value) {
    this.page.filterParams[col] = value;
    this.page.pageNumber = 0;
    this.getItems();
  }

  techicianClick(rowData) {
    this.router.navigate(['technicians/edit/' + rowData['TE1_ID']], {queryParams: {openHoursTab: true}});
  }

  dateFormat(date){
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    let explodeDate = date.split(".");
    return explodeDate[2]+'-'+months[ parseInt(explodeDate[0])-1];
  }

  deleteClick() {
      if (this.selected) {
          this.confirmationService.confirm({
            message: _lang('Are you sure that you want to perform this action?'),
            rejectVisible: true,
            accept: () => {
              this.loading = true;
              this.hourService.delete(this.selected['HO1_ID']).subscribe( result => {
                  this.getItems();
                  this.selected = false;
              },
              error => {
                  this.loading = false;
                  this.toastr.error(_lang('On delete hour error occured'), _lang('Service Error'), {
                      timeOut: 3000,
                  });
              });
            }
          });
      }
  }
}
