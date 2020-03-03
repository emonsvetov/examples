import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { Page } from '../../model/page';

import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';
import { permissions } from '../../permissions';
import { CompanyService } from '../../services/company.service';
import { MatDialog } from '@angular/material';
import { ConfirmationService } from 'primeng/api';
import { LanguageEditorModalComponent } from '../../components/language-editor-modal/language-editor-modal.component';

import { TitleService } from 'app/services/title.service';
import { ExportModalComponent } from '../../components/export-modal/export-modal.component';
import { _lang } from '../../pipes/lang';
import { FormControl } from '@angular/forms';
import { Subject, Subscription } from 'rxjs';
import { takeUntil } from 'rxjs/internal/operators';
import { HeaderService } from '../../services/header.service';

@Component({
    templateUrl: './companies.component.html',
    styleUrls: ['./companies.component.scss'],
    providers: [
        CompanyService,
        ConfirmationService
    ],
    preserveWhitespaces: true
})

export class CompaniesComponent implements OnInit {

  private permissions = permissions;

  @ViewChild('companiesView')     companiesView: ElementRef;
  @ViewChild('actionbar')         actionbar: ElementRef;
  @ViewChild('content')           content: ElementRef;
  @ViewChild('tableHeader')       tableHeader: ElementRef;
  @ViewChild('tableFilter')       tableFilter: ElementRef;
  @ViewChild('paginator')         paginator: ElementRef;

  private scrollHeight: string = '50px';

  /** control for the selected filters */
  public countryCtrl: FormControl = new FormControl();
  public countryFilterCtrl: FormControl = new FormControl();
  /** Subject that emits when the component has been destroyed. */
  private _onDestroyCountry = new Subject<void>();
  /** lists of filtered items by search keyword */
  public filteredCountries: any[] = [];
  /** filter object with selected filters properties */
  private filter: any = {};

  flagsDP: any[] = [];
  cols: any[] = [];
  items: any[] = [];
  countries: any[] = [];

  private loading = false;
  private page = new Page();
  private selected: any = null;
  private size: number = 50;
  private totalRecords: number = 0;
  private subscription: Subscription;

    constructor(
    private toastr: ToastrService,
    private companyService: CompanyService,
    private headerService: HeaderService,
    public  dialog: MatDialog,
    private router: Router,
    private confirmationService: ConfirmationService,
    private titleService: TitleService,
    private route: ActivatedRoute) {
    this.page.size = this.size;
    this.page.pageNumber = 0;
    this.subscription = this.headerService.subscribe({
        next: (v) => {
            this.page.pageNumber = 0;
            this.page.filterParams = {};
            this.loading = true;
            this.getItems();
        }
    });

}

  ngOnInit() {
    this.titleService.setNewTitle(this.route);

    this.cols = [
        { field: 'CO1_SHORT_CODE',    header: _lang('CO1_SHORT_CODE'),     visible: true, export: {visible: true, checked: true}, width: 90 },
        // { field: 'CY1_ID',            header: _lang('CY1_NAME'), visible: true, export: {visible: true, checked: true}, width: 140 },
        { field: 'CO1_NAME',          header: _lang('CO1_NAME'),           visible: true, export: {visible: true, checked: true}, width: 140},
        { field: 'CO1_ADDRESS1',      header: _lang('#AddressStreet1#'),   visible: true, export: {visible: true, checked: true}, width: 140 },
        // { field: 'CO1_ADDRESS2',      header: _lang('#AddressStreet2#'), visible: true, export: {visible: true, checked: true}, width: 140},
        { field: 'CO1_CITY',          header: _lang('#AddressCity#'),      visible: true, export: {visible: true, checked: true}, width: 120},
        { field: 'CO1_STATE',         header: _lang('#AddressState#'),     visible: true, export: {visible: true, checked: true}, width: 60},
        { field: 'CY1_SHORT_CODE',    header: _lang('CY1_SHORT_CODE'),     visible: true, export: {visible: true, checked: true}, width: 100},
        { field: 'CO1_ZIP',           header: _lang('#AddressPostalCode#'),visible: true, export: {visible: true, checked: true}, width: 100},
        { field: 'CO1_PHONE',         header: _lang('#Phone#'),            visible: true, export: {visible: true, checked: true}, width: 120},
        { field: 'CO1_FAX',           header: _lang('#Fax#'),              visible: true, export: {visible: true, checked: true}, width: 120},
        { field: 'CO1_MITCHELL_FLAG', header: _lang('CO1_MITCHELL_FLAG'),  visible: true, export: {visible: true, checked: true}, width: 80, align: 'center'},
        { field: 'CO1_CCC_FLAG',      header: _lang('CO1_CCC_FLAG'),       visible: true, export: {visible: true, checked: true}, width: 80, align: 'center'},
        { field: 'CO1_LITE',          header: _lang('CO1_LITE'),           visible: true, export: {visible: true, checked: true}, width: 80, align: 'center'}
    ];

      this.flagsDP = [
          { label: _lang('#AllRecords#'),   value: ''},
          { label: _lang('Yes'),            value: '1'},
          { label: _lang('No'),             value: '0'}
      ];

      this.companyService.preloadData().subscribe( data => {
          this.prepareData(data);
          this.getItems();
      });

      this.countryFilterCtrl.valueChanges
          .pipe(takeUntil(this._onDestroyCountry))
          .subscribe(() => {
              this.filterCountries();
          });
  }

    private filterCountries() {
        if (!this.countries) {
            return;
        }
        // get the search keyword
        let search = this.countryFilterCtrl.value;
        if (!search) {
            this.filteredCountries = this.countries;
            return;
        } else {
            search = search.toLowerCase();
        }
        // filter the countries
        this.filteredCountries = this.countries.filter(country => country['CY1_SHORT_CODE'].toLowerCase().indexOf(search) > -1);
    }

    getItems() {
        let id = this.headerService.CO1_ID;
        if(!isNaN(id)){
            this.page.filterParams['CO1_ID'] = this.headerService.CO1_ID;
        }
        this.companyService.getCompanies(this.page).subscribe(res => {
                this.items = res.data;
                this.totalRecords = res.count;

                setTimeout(() => {this.scrollHeight = this.getScrollHeight() + 'px';});

                this.loading = false;
            },
            error => {
                this.loading = false;
                this.toastr.error(_lang('Companies') + ' ' + _lang('loading error occured'), _lang('Service Error'), {
                    timeOut: 3000,
                });
            });
    }

    prepareData(data) {
        this.countries = [{CY1_ID: null, CY1_SHORT_CODE: _lang('#AllRecords#')}];
        let countriesArr = data.countries.map( country => {
            return {
                CY1_ID:   country.CY1_ID,
                CY1_SHORT_CODE: country.CY1_SHORT_CODE
            }
        }).filter( country => {
            return country.CY1_SHORT_CODE && country.CY1_SHORT_CODE.length > 0;
        }).sort((a, b) => {
            if (a.CY1_SHORT_CODE < b.CY1_SHORT_CODE)
                return -1;
            if (a.CY1_SHORT_CODE > b.CY1_SHORT_CODE)
                return 1;
            return 0;
        });
        this.countries = this.countries.concat(countriesArr);
        this.filterCountries();
    }

    ngOnDestroy() {
        if (this.subscription)
            this.subscription.unsubscribe();
        this._onDestroyCountry.next();
        this._onDestroyCountry.complete();
    }

    getScrollHeight(): number {
        return (this.companiesView.nativeElement.parentElement.parentElement.parentElement.offsetHeight) -
            (this.actionbar.nativeElement.offsetHeight + 48) - (this.tableHeader.nativeElement.offsetHeight +
            this.tableFilter.nativeElement.offsetHeight + this.paginator.nativeElement.offsetHeight);
    }

    onPage(event) {
        this.loading = true;
        this.page.size = event.rows;
        this.page.pageNumber = (event.first / event.rows);
        this.getItems();
    }

    buttonPress(e, col, value) {
        if ( e.keyCode === 13 ) {
            this.filterChanged(col, value);
        }
    }

    filterChanged(col, value) {
        this.loading = true;
        if ((col === 'ORDERED_ON' || col === 'COMPLETED_ON' || col === 'MODIFIED_ON' || col === 'CREATED_ON') && value === '01-01-1970') {
            this.page.filterParams[col] = '';
        } else {
            this.page.filterParams[col] = value;
        }
        this.page.pageNumber = 0;
        this.getItems();
    }

    openLanguageEditor() {
        let dialogRef = this.dialog.open(LanguageEditorModalComponent, {
            width: window.innerWidth-60+'px',
            height: window.innerHeight-40+'px',
            role: 'dialog',
            data: {
                title: _lang('Company List'),
                componentRef: this,
                data: [],
                componentName: 'CompaniesComponent'
            },
        });
    }

    addClick() {
        this.router.navigate(['companies/add']);
    }

    cloneRecord() {
      if (this.selected) {
          this.router.navigate(['companies/copy/' + this.selected.CO1_ID]);
      }
    }

    exportClick() {
        let dialogRef = this.dialog.open(ExportModalComponent, {
            width: 400 + 'px',
            height: window.innerHeight - 40 + 'px',
            role: 'dialog',
            data: {cols: this.cols},
        });

        dialogRef.beforeClose().subscribe( columns => {
            if (columns) {
                this.page.filterParams['columns'] = columns;

                this.companyService.downloadXlsx(this.page).subscribe( result => {
                        this.loading = false;
                    },
                    error => {
                        this.loading = false;
                        this.toastr.error(_lang('Generate xml error occured'), _lang('Service Error'), {
                            timeOut: 3000,
                        });
                    });
            }
        })
    }

    editRecord(rowData?) {
      if (this.selected || rowData) {
          const CO1_ID = (rowData && rowData.CO1_ID) ? rowData.CO1_ID : this.selected.CO1_ID;
          this.router.navigate(['companies/edit/' + CO1_ID]);
      }
    }

    deleteRecord() {
      if (this.selected) {
        this.confirmationService.confirm({
            message: _lang('Are you sure that you want to perform this action?'),
            accept: () => {
                this.loading = true;
                this.companyService.delete(this.selected.CO1_ID).subscribe(result => {
                    this.headerService.next();
                    this.items = this.items.filter( item => {
                        return item.CO1_ID === this.selected.CO1_ID ? false : true;
                    });
                    this.loading = false;
                });
            }
        });
      }
    }
}
