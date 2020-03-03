import { Component, ElementRef, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { MatDialog } from '@angular/material';
import { CompanyService } from '../../../services/company.service';
import { permissions } from '../../../permissions';
import { CountryService } from '../../../services/country.service';
import { LanguageEditorModalComponent } from '../../../components/language-editor-modal/language-editor-modal.component';
import { TimezonesService } from '../../../services/timezones.service';
import { SelectCountriesComponent } from '../../../components/select-countries/select-countries.component';
import { TitleService } from 'app/services/title.service';
import { _lang } from '../../../pipes/lang';
import { HeaderService } from '../../../services/header.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-company-form',
  templateUrl: './company-form.component.html',
  styleUrls: ['./company-form.component.scss'],
  providers: [
    CompanyService
  ],
  preserveWhitespaces: true
})
export class CompanyFormComponent implements OnInit {

  private errors: Array<any> = [];
  private action: String = '';
  private countries: Array<any> = [];
  private regiones: Array<any> = [];
  private timezones: Array<any> = [];
  private selectedRegion: any;

  private permissions = permissions;
  private record: any;

  constructor(
      private route: ActivatedRoute,
      private companyService: CompanyService,
      public dialog: MatDialog,
      private elementRef: ElementRef,
      private countyService: CountryService,
      private timezonesService: TimezonesService,
      private titleService: TitleService,
      private headerService: HeaderService,
      private toastr: ToastrService
  ) {

  }

  ngOnInit() {
    this.titleService.setNewTitle(this.route);
    this.record = this.companyService.initialize();
    this.action = this.route.snapshot.url[0].toString();
    var CO1_ID = this.route.snapshot.params.id || 0;
    this.companyService.getById(CO1_ID).subscribe( data => {
      this.countyService.getAllCountries().subscribe( result => {
        this.countries = result.data.map( item => {
            return {
                value: parseInt(item.CY1_ID),
                text:  item.CY1_SHORT_CODE + ': ' + item.CY1_NAME
            }
        });

        data['CY1_ID']            = parseInt(data['CY1_ID']);
        data['CO1_MITCHELL_FLAG'] = parseInt(data['CO1_MITCHELL_FLAG']);
        data['CO1_CCC_FLAG']      = parseInt(data['CO1_CCC_FLAG']);
        data['CO1_LITE']          = parseInt(data['CO1_LITE']);

        this.record = data;
        if (this.regiones) {
          this.setTimeZone(data.CO1_TIMEZONE);
        }
        this.companyService.getCompanyLastShortCode().subscribe(res => {
              this.record['CO1_SHORT_CODE'] = (parseInt(res['CO1_SHORT_CODE'].toString()) + 1).toFixed(0);
            },
            error => {
              this.toastr.error(_lang('Company Short Code') + ' ' + _lang('loading error occured'), _lang('Service Error'), {
                timeOut: 3000,
              });
            });
      });
    });

    this.regiones = this.timezonesService.getStoreTimezones();
  }

  openLanguageEditor(){
      let dialogRef = this.dialog.open(LanguageEditorModalComponent, {
          width: window.innerWidth-60+'px',
          height: window.innerHeight-40+'px',
          role: 'dialog',
          data: {
            title: _lang('Company Form'),
            componentRef: this,
            data: [],
            componentName: 'CompanyFormComponent'
          },
      });
  }

  cancelClick() {
    window.history.back();
  }

  saveClick() {
    if (this.action === 'add' || this.action === 'copy') {
      this.companyService.create(this.record).subscribe(result => {
        if (result.success) {
          this.headerService.next();
          this.cancelClick();
        } else {
          this.errors = result.errors;
        }
      });
    } else if (this.action === 'edit') {
      this.companyService.update(this.record).subscribe(result => {
        if (result.success) {
          this.headerService.next();
          this.cancelClick();
        } else {
          this.errors = result.errors;
        }
      });
    }
  }

  onRegionChange() {
    this.timezones = this.selectedRegion ? this.selectedRegion.list : [];
  }

  setTimeZone(zoneId) {
    for (var i=0; i<this.regiones.length; i++) {
      let reg = this.regiones[i];
      let zone = reg.list.find(z => z.Time_zone_id === zoneId);
      if(zone) {
        this.selectedRegion = reg;
        break;
      }
    }

    this.onRegionChange();
  }

  selectCountry() {
    let dialogRef = this.dialog.open(SelectCountriesComponent, {
      width: window.innerWidth-100+'px',
      height: window.innerHeight-80+'px',
      role: 'dialog',
      data: {title: _lang('Select Country'), CY1_ID: this.record.CY1_ID, componentRef: this.elementRef},
    });

    dialogRef.beforeClose().subscribe( selected => {
      if (selected) {
        this.record.CY1_ID = selected['CY1_ID'];
        this.record.CY1_SHORT_CODE = selected['CY1_SHORT_CODE'];
        this.record.CY1_NAME = selected['CY1_NAME'];
      }
    })
  }

  clearCountry() {
    this.record.CY1_ID = 0;
    this.record.CY1_SHORT_CODE = '';
    this.record.CY1_NAME = '';
  }
}
