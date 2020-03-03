import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { permissions } from '../../../permissions';
import { ActivatedRoute, Router } from '@angular/router';
import { MatDialog, MatDialogRef } from '@angular/material';
import { TitleService } from '../../../services/title.service';
import { LanguageEditorModalComponent } from '../../../components/language-editor-modal/language-editor-modal.component';
import { _lang } from '../../../pipes/lang';
import { CategoryFinderModalComponent } from '../../../components/category-finder-modal/category-finder-modal.component';
import { InventoryFinderModalComponent } from '../../../components/inventory-finder-modal/inventory-finder-modal.component';
import { MasterVendorFinderModalComponent } from '../../../components/master-vendor-finder-modal/master-vendor-finder-modal.component';
import { environment } from '../../../../environments/environment';
import { MasterDataService } from '../../../services/master.data.service';
import { Location } from '@angular/common';
import { ShopService } from '../../../services/shop.service';
import { VendorService } from '../../../services/vendor.service';
import { MeasurementUnitService } from '../../../services/measurement.unit.service';
import { FileUploaderModalComponent } from '../../../components/file-uploader-modal/file-uploader-modal.component';
import { HeaderService} from '../../../services/header.service';
import { Page } from '../../../model/page';
import * as _ from 'lodash';
import { GlCodesService } from '../../../services/glcodes.service';
import { ConfirmationService } from 'primeng/api';
import { MasterCatalogService } from '../../../services/master-catalog.service';
import { LinkCatalogPartsModalComponent } from '../../../components/link-catalog-parts-modal/link-catalog-parts-modal.component';
import { ToastrService } from 'ngx-toastr';
import { PriceService } from '../../../services/price.service';
import { PriceChangeLogModalComponent } from '../../../components/price-change-log-modal/price-change-log-modal.component';
import { FileUploadService } from '../../../services/file-upload.service';
import { GroupsService } from '../../../services/groups';
import { AuthService } from '../../../auth/auth.service';

@Component({
  selector: 'app-master-data-form',
  templateUrl: './master-data-form.component.html',
  styleUrls: ['./master-data-form.component.scss'],
  providers: [
    MasterDataService,
    ShopService,
    VendorService,
    MeasurementUnitService,
    GlCodesService,
    ConfirmationService,
    MasterCatalogService,
    PriceService,
    FileUploadService
  ],
  preserveWhitespaces: true
})
export class MasterDataFormComponent implements OnInit {
  @ViewChild('masterDataForm')  masterForm: ElementRef;
  @ViewChild('actionbar')       actionbar:  ElementRef;
  @ViewChild('cardHeader')      cardHeader:  ElementRef;
  @ViewChild('gridAction')      gridAction:  ElementRef;
  @ViewChild('tableHeader')     tableHeader:  ElementRef;

  private errors: Array<any> = [];
  private action: String = '';
  private loaderWidth: String = '0px';

  public loading: boolean = true;

  private permissions = permissions;
  private groupsservice = GroupsService;
  private record: any;
  private apiUrl = environment.API_URL;

  private catalogPartSearchMode: boolean = false;

  private discontinued: Array<any> = [{label: 'Available', value: 0}, {label: 'Discontinued', value: 1}];
  private umData: Array<any> = [{'UM1_NAME': '- Select -', 'UM1_ID': '', 'UM1_SIZE': ''}];
  private VOCUnitDP: Array<any> = [{label: 'G/L', value: 0}, {label: 'LBS', value: 1}];

  private locationMasterData: Array<any> = [];
  private vendors: Array<any> = [];
  private glCodes: Array<any> = [];
  private um: Array<any> = [];

  private fileToUpload: File = null;

  deleteConfirmation: string = '';

    constructor(private route: ActivatedRoute,
              private masterDataService: MasterDataService,
              private masterCatalogService: MasterCatalogService,
              private auth: AuthService,
              private shopService: ShopService,
              private vendorService: VendorService,
              private headerService: HeaderService,
              private glCodesService: GlCodesService,
              private measurementUnitService: MeasurementUnitService,
              private confirmationService: ConfirmationService,
              private fileUploadService: FileUploadService,
              private router: Router,
              private toastr: ToastrService,
              public  dialog: MatDialog,
              private elementRef: ElementRef,
              private titleService: TitleService,
              private location: Location) { }

  ngOnInit() {
    this.titleService.setNewTitle(this.route);

    this.action = this.route.snapshot.url[0].toString();
    this.record = this.masterDataService.initialize();

    this.loaderWidth = this.masterForm.nativeElement.parentElement.parentElement.offsetWidth + 'px';

    var me = this;
    (new Promise((resolve, reject) => {
      me.masterDataService.getById(this.route.snapshot.params.id).subscribe( data => {
          data['MD1_ID'] = data['MD1_ID'] ? parseInt(data['MD1_ID']) : 0;
          data['MD1_BODY'] = parseInt(data['MD1_BODY']);
          data['MD1_DETAIL'] = parseInt(data['MD1_DETAIL']);
          data['MD1_CENTRAL_ORDER_FLAG'] = parseInt(data['MD1_CENTRAL_ORDER_FLAG']);
          data['MD1_FAVORITE'] = parseInt(data['MD1_FAVORITE']);
          data['MD1_PAINT'] = parseInt(data['MD1_PAINT']);
          data['MD1_VOC_FLAG'] = parseInt(data['MD1_VOC_FLAG']);
          data['MD1_VOC_UNIT'] = parseInt(data['MD1_VOC_UNIT']);
          data['MD0_ORIGINAL_ID'] = parseInt(data['MD0_ID']) || 0;
          data['MD0_ID'] = parseInt(data['MD0_ID']) || 0;
          data['MD0_UNIT_PRICE'] = parseFloat(data['MD0_UNIT_PRICE'] || 0).toFixed(2);
          data['MD1_ORIGINAL_UNIT_PRICE'] = parseFloat(data['MD1_ORIGINAL_UNIT_PRICE'] || 0).toFixed(2);
          data['MD1_SELL_PRICE'] = me.calculateSellPrice(data);
          data['CA2_ID'] = data['CA2_ID'] ? data['CA2_ID'] : 0;
          data['FI1_ID'] = data['FI1_ID'] ? data['FI1_ID'] : 0;
          this.record = data;
          if (this.action === 'clone') {
              this.record['MD1_ID'] = 0;
          }
          resolve(this.record);
      })
    })).then(function (record) {
      return new Promise((resolve, reject) => {
        var page = new Page();
        me.glCodesService.getItems(page).subscribe(res => {
            if (res.data && res.data.length) {
                me.glCodes = res.data;
            } else {
                me.glCodes = [{GL1_ID: 0, GL1_NAME: '--No Data--'}];
            }
            resolve(record);
          });
      });
    }).then(function (record) {
      return new Promise((resolve, reject) => {
        var page = new Page();
        page.filterParams = {CO1_ID: me.headerService.CO1_ID};
          me.vendorService.getVendors(page).subscribe( res => {
            me.vendors = res.data['items'];
            resolve(record);
          });
      });
    }).then(function (record) {
      return new Promise((resolve, reject) => {
          me.measurementUnitService.getItems({}).subscribe( result => {
            me.um = result.data;
            me.umData.push(...result.data);
            resolve(record);
          });
      });
    }).then(function (record) {
      return new Promise((resolve, reject) => {
          me.shopService.getMasterData(record['MD1_ID']).subscribe( data => {
              me.locationMasterData = data.map( item => {
                  item['MD2_ACTIVE']         = parseInt(item['MD2_ACTIVE']);
                  item['MD2_PARTIAL']        = parseInt(item['MD2_PARTIAL']);
                  item['UM2_FACTOR']         = item['UM2_FACTOR'] || 1;
                  item['UM2_PRICE_FACTOR']   = item['UM2_PRICE_FACTOR'] || 1;
                  item['CENTRAL_ORDER_ONLY'] = record['MD1_CENTRAL_ORDER_FLAG'] ? true : false;
                  return item;
              });

              me.loading = false;
              resolve(record);
          });
      });
    });
  }

  cancelClick() {
    this.location.back();
  }

  priceChangeLogClick() {
    let dialogRef = this.dialog.open(PriceChangeLogModalComponent, {
      width: window.innerWidth-60+'px',
      height: window.innerHeight-40+'px',
      role: 'dialog',
      data: {title: _lang('#PriceChangeLog#'), componentRef: this, MD1_ID: this.record.MD1_ID},
    });
  }

  openLanguageEditor() {
    let dialogRef = this.dialog.open(LanguageEditorModalComponent, {
      width: window.innerWidth-60+'px',
      height: window.innerHeight-40+'px',
      role: 'dialog',
      data: {
          title: _lang('Customer Data Form'),
          componentRef: this,
          data: [],
          componentName: 'MasterDataFormComponent'
      },
    });
  }

  selectCategory() {
      let dialogRef = this.dialog.open(CategoryFinderModalComponent, {
        width: window.innerWidth-100+'px',
        height: window.innerHeight-80+'px',
        role: 'dialog',
        data: {title: _lang('Select Category'), CA1_ID: this.record.CA1_ID, componentRef: this.elementRef},
      });

      dialogRef.beforeClose().subscribe( selected => {
        if (selected) {
          this.record.CA1_ID = selected['CA1_ID'];
          this.record.CA1_NAME = selected['CA1_NAME'];
        }
      });
  }

  clearCategory() {
    this.record.CA1_ID = 0;
    this.record.CA1_SHORT_CODE = '';
    this.record.CA1_NAME = '';
  }

  selectMasterCategory() {
    let dialogRef = this.dialog.open(InventoryFinderModalComponent, {
      width: window.innerWidth-100+'px',
      height: window.innerHeight-100+'px',
      role: 'dialog',
      data: {title: _lang('Select Nuventory Category'), C00_ID: this.record.C00_ID, componentRef: this.elementRef},
    });

    dialogRef.beforeClose().subscribe( selected => {
      if (selected) {
        this.record.C00_ID = selected['C00_ID'];
        this.record.C00_NAME = selected['C00_NAME'];
      }
    });
  }

  clearMasterCategory() {
    this.record.C00_ID = 0;
    this.record.C00_SHORT_CODE = '';
    this.record.C00_NAME = '';
  }

  selectMasterVendor() {
    let dialogRef = this.dialog.open(MasterVendorFinderModalComponent, {
      width: window.innerWidth-100+'px',
      height: window.innerHeight-100+'px',
      role: 'dialog',
      data: {title: _lang('Select Mater Vendor'), VD0_ID: this.record.VD0_ID, componentRef: this.elementRef},
    });

    dialogRef.beforeClose().subscribe( selected => {
      if (selected) {
        this.record.VD0_ID = selected['VD0_ID'];
        this.record.VD0_SHORT_CODE = selected['VD0_SHORT_CODE'];
        this.record.VD0_NAME = selected['VD0_NAME'];
      }
    });
  }

  clearMasterVendor() {
    this.record.VD0_ID = 0;
    this.record.VD0_SHORT_CODE = '';
    this.record.VD0_NAME = '';
  }

  defaultPricingChanged(e) {
    var um = this.umData.filter( item => {
      if( parseInt(item.UM1_ID) == parseInt(e.value)){
        return item;
      }
    });
    this.record.MD1_UM1_PRICE_NAME = um[0]['UM1_NAME'];
    if(this.record.MD1_CENTRAL_ORDER_FLAG){
      this.locationMasterData = this.locationMasterData.map( item => {
        if( parseInt(item['LO1_CENTER_FLAG']) ){
          item['UM1_PURCHASE_ID'] = this.record.UM1_PRICING_ID;
        }
        return item;
      });
    }
  }

  MD1_SAFETY_URL_GO() {
    if(this.record.MD1_SAFETY_URL.length){
      window.open(this.record.MD1_SAFETY_URL, '_blank');
    }
  }

  marckupChange() {
    var record = _.clone(this.record);
    record.MD1_SELL_PRICE = this.calculateSellPrice(record);
    record.MD1_UNIT_PRICE = record.MD1_ORIGINAL_UNIT_PRICE;
    this.record = record;
  }

  calculateSellPrice(record){
    return (record.MD1_ORIGINAL_UNIT_PRICE * ( 1 +record.MD1_MARKUP * 0.01)).toFixed(2);
  }

  vendorChange(value, row){
    this.locationMasterData = this.locationMasterData.map( item => {
      if( parseInt(row.LO1_ID) == parseInt(item.LO1_ID)){
        item['VD1_ID'] = value;
      }
      return item;
    });
  }

  saveClick() {
    var me = this;
    (new Promise((resolve, reject) => {

        me.record.locationData = me.locationMasterData;
        if ( this.action !== 'add' && this.action !== 'clone' && me.record['MD0_ORIGINAL_ID'] !== parseInt(me.record['MD0_ID'])) {
            this.confirmationService.confirm({
                header:  _lang('CATALOG PART CHANGED'),
                message: _lang('Are you sure you want to change the catalog part?\nThis action may affect shop-level vendor selection.'),
                accept: () => {
                    resolve();
                }
            });
        } else {
            me._save();
        }
    })).then(function () {
        return new Promise((resolve, reject) => {

            var page = new Page();
            page.filterParams['MD0_PART_NUMBER'] = me.record.MD1_PART_NUMBER;
            me.locationMasterData.forEach(locationData => {
                me.vendors.forEach((vendor, index) => {
                    if (vendor['VD1_ID'] == locationData['VD1_ID']) {
                        if (vendor['VD0_ID']) {
                            page.filterParams['VD0_ID[' + index + ']'] = vendor['VD0_ID'];
                        }
                    }
                });
            });

            //(application.MAccessControl.isManufacturer()?"AND md0.VD0_ID = '" + application.MAccessControl.parent.VD0_ID + "'":"");
            me.masterCatalogService.findLinkedParts(page).subscribe((items: any) => {
                resolve(items);
            });
        });
    }).then(function (items: any) {
        return new Promise((resolve, reject) => {
            if (!items.length) {
                var page = new Page();
                page.filterParams['MD0_PART_NUMBER'] = me.record.MD1_PART_NUMBER;
                me.masterCatalogService.findLinkedParts(page).subscribe((items: any) => {
                    if (!items.length) {
                      resolve(null);
                    } else if (items.length == 1) {

                        var message = "Would you like to link the following catalog part? <br/>" +
                            "Vendor: " + items[0]["VD0_NAME"] + "<br/>" +
                            "Part Number: " + items[0]["MD0_PART_NUMBER"] + "<br/>" +
                            "Description: " + items[0]["MD0_DESC1"];

                        me.confirmationService.confirm({
                            header:  _lang("Matching Catalog Part Found"),
                            message: message,
                            accept: () => {
                                resolve(items[0]['MD0_ID']);
                            }
                        });
                    } else {
                        me.openLinkCatalogParts(items).beforeClose().subscribe(selected => {
                            resolve(selected['MD0_ID']);
                        });
                    }
                });
            } else if (items.length == 1) {
                resolve(items[0]['MD0_ID']);
            } else {
                me.openLinkCatalogParts(items).beforeClose().subscribe(selected => {
                    if(selected){
                        resolve(selected['MD0_ID']);
                    }else{
                        reject();
                    }
                });
            }
        });
    }).then( function (MD0_ID: number) {
        me.record.MD0_ID = MD0_ID;
        let page = new Page();
        page.filterParams['MD1_PART_NUMBER'] = me.record.MD1_PART_NUMBER;
        me.masterDataService.checkDuplicates(page).subscribe( (data:any) => {
            //insert
            if(me.action === 'add' && data.num && data.num > 0){
                var message = _lang(":ErrorCodeDuplicate:", [me.record.MD1_PART_NUMBER]) + _lang("#FindAnotherCode#");
                var title   = _lang( "#Duplicate#", [ _lang("MD1_PART_NUMBER") ]);

                me.toastr.error( message, title, {
                  timeOut: 3000,
                });
            }else{
                me._save();
            }
        });
    });
  }

  private _save()
  {
      if (this.fileToUpload) {
          this.uploadFile();
      } else {
          this.doSave();
      }
  }

    doSave() {
        if (this.action === 'add') {
            this.masterDataService.create(this.record).subscribe( result => {
                if (result.success) {
                    this.cancelClick();
                } else {
                    this.errors = result.errors;
                    this.toastr.error('Unable to save Part info in database.', 'Error', {
                        timeOut: 3000,
                        tapToDismiss: true
                    });
                }
            });
        } else {
            this.masterDataService.update(this.record).subscribe( result => {
                if (result.success) {
                    this.cancelClick();
                } else {
                    this.errors = result.errors;
                    this.toastr.error('Unable to update Part info in database.', 'Error', {
                        timeOut: 3000,
                        tapToDismiss: true
                    });
                }
            });
        }
    }


  openLinkCatalogParts( items ): MatDialogRef<LinkCatalogPartsModalComponent> {
    let dialogRef = this.dialog.open(LinkCatalogPartsModalComponent, {
      width: window.innerWidth-100+'px',
      height: window.innerHeight-100+'px',
      role: 'dialog',
      data: {title: _lang('Link Catalog Part'), items: items, componentRef: this.elementRef},
    });

    return dialogRef;
  }

  triggerCatalogPartSearchMode(){
    this.catalogPartSearchMode = !this.catalogPartSearchMode;
  }

  catalogPartChange(part) {
    if(!this.record.MD1_ID){
        this.masterCatalogService.getById(part.MD0_ID).subscribe( result => {
            if (result[0]) {
                  let data = result[0];
                  this.record.MD0_ID = data.MD0_ID;
                  this.record.MD1_PART_NUMBER        = data.MD0_PART_NUMBER;
                  this.record.MD1_VENDOR_PART_NUMBER = data.MD0_PART_NUMBER;
                  this.record.MD1_DESC1 = data.MD0_DESC1;
                  this.record.MD1_UPC1 = data.MD0_UPC1;
                  this.record.UM1_NAME = part.UM1_NAME;
                  this.record.C00_ID = data.C00_ID;
                  this.record.C00_NAME = data.C00_NAME;
                  this.record.CA0_ID = data.CA0_ID;
                  this.record.CA0_NAME = data.CA0_NAME;
                  this.record.MD1_MARKUP = data.MD0_MARKUP;
                  this.record.MD1_UNIT_PRICE = parseFloat(data.MD0_UNIT_PRICE).toFixed(2);
                  this.record.MD1_ORIGINAL_UNIT_PRICE = parseFloat(data.MD0_UNIT_PRICE).toFixed(2);
                  this.record.MD1_SAFETY_URL = data.MD0_SAFETY_URL;
                  this.record.MD1_SELL_PRICE = parseFloat(data.MD0_SELL_PRICE).toFixed(2);
                  this.record.UM1_PRICING_ID = data.UM1_PRICE_ID;
                  this.record.UM1_PRICE_SIZE = parseInt(data.UM1_PRICE_SIZE);
                  this.record.MD1_UM1_PRICE_NAME = part.UM1_NAME;
                  this.record.MD1_VOC_FLAG  = parseInt(data.MD0_VOC_FLAG);
                  this.record.MD1_VOC_MFG   = data.MD0_VOC_MFG;
                  this.record.MD1_VOC_CATA  = data.MD0_VOC_CATA;
                  this.record.MD1_VOC_VALUE = data.MD0_VOC_VALUE;
                  this.record.MD1_VOC_UNIT  = parseInt(data.MD0_VOC_UNIT);
                  this.record.CA1_ID   = parseInt(data.CA1_ID);
                  this.record.CA1_NAME = data.CA1_NAME;

                  if ( (this.action === 'clone' || this.action === 'add') && this.locationMasterData) {
                      this.locationMasterData = this.locationMasterData.map( item => {
                          item['UM1_RECEIPT_ID']   = data.UM1_DEFAULT_RECEIVE;
                          item['UM2_FACTOR']       = data.UM1_DEFAULT_RECEIVE_FACTOR;
                          item['UM1_PURCHASE_ID']  = data.UM1_DEFAULT_PURCHASE;
                          item['UM2_PRICE_FACTOR'] = data.UM1_DEFAULT_PURCHASE_FACTOR;
                          return item;
                      });
                  }
            }
        });
    }

    this.record.MD0_ID          = part.MD0_ID;
    this.record.MD0_PART_NUMBER = part.MD0_PART_NUMBER;
    this.record.MD0_DESC1       = part.MD0_DESC1;
    this.record.VD0_NAME        = part.VD0_NAME;
    this.record.VD0_ID          = part.VD0_ID;
    this.record.VD0_SHORT_CODE  = part.VD0_SHORT_CODE;

    this.triggerCatalogPartSearchMode();
  }

  chooseImage() {
    let dialogRef = this.dialog.open(FileUploaderModalComponent, {
      width: window.innerWidth-100+'px',
      height: window.innerHeight-100+'px',
      role: 'dialog',
      data: {title: _lang('File Upload'), componentRef: this.elementRef},
    });

    dialogRef.beforeClose().subscribe( result => {
      if (result) {
        this.fileToUpload = result['fileToUpload'];
        this.record.FI1_FILE_PATH = result['imageUrl'];
        this.record.FI1_FILE_PATH_EXIST = true;
      }
    });
  }

    uploadFile() {
        let me = this;
        this.fileUploadService.uploadFile(this.fileToUpload, 'image').subscribe( result => {
            if (result && result.data && result.data.success === true ) {
                me.record.FI1_ID = result.data.FI1_ID;
                me.record.FI1_FILE_PATH = result.data.imageUrl;
                me.record.FI1_FILE_PATH_EXIST = true;
                me.doSave();
            } else {
                me.toastr.error(_lang('Image uploading error occured'), _lang('Service Error'), {
                    timeOut: 3000,
                });
            }
        });
    }

  removeImage() {
      this.record.FI1_ID = 0;
      this.record.FI1_FILE_PATH = '';
      this.record.FI1_FILE_PATH_EXIST = false;
      this.fileToUpload = null;
  }


  public centralOrderChange(event){
    var centralLocationItems = this.locationMasterData.filter( item => {
      return item['LO1_CENTER_FLAG'];
    });
    var centralLocation = centralLocationItems.length ? centralLocationItems[0] : null;
    var me = this;
    var locationMasterData = this.locationMasterData.map( item => {
      if( parseInt(item['LO1_CENTER_FLAG']) ){
        item['MD2_ACTIVE'] = item['MD2_ACTIVE'] || me.record.MD1_CENTRAL_ORDER_FLAG;
        if ( me.record.MD1_CENTRAL_ORDER_FLAG ) {
          item['UM1_PURCHASE_ID'] = me.record.UM1_PRICING_ID;
        }
      }
      return item;
    });

    this.locationMasterData = this.setUmReceipt(locationMasterData, centralLocation);
  }

  setUmReceipt(locationMasterData, centralLocation){
    if(this.record.MD1_CENTRAL_ORDER_FLAG && centralLocation){
      locationMasterData = locationMasterData.map( item => {
        if(!parseInt(item['LO1_CENTER_FLAG']) ){
          item['UM2_PRICE_FACTOR'] = centralLocation.UM2_FACTOR;
          item['UM1_PURCHASE_ID']  = centralLocation.UM1_RECEIPT_ID;
        }
        return item;
      });
    }
    return locationMasterData;
  }

  umFactorChange(event, row) {
    if (parseInt(row['LO1_CENTER_FLAG'])) {
        this.locationMasterData = this.setUmReceipt(this.locationMasterData, row);
    }

    this.locationMasterData = this.locationMasterData.map( item => {
        if(item['UM1_PURCHASE_ID'] == row['UM1_PURCHASE_ID'] &&
           item['UM1_RECEIPT_ID']  == row['UM1_RECEIPT_ID'] ){
            item['UM2_FACTOR'] = row['UM2_FACTOR'];
        }
        return item;
    });
  }

  umPurchaseChange(value, row) {
      if (value.hasOwnProperty('UM1_ID')) {
          var locationMasterData = this.locationMasterData.map( item => {
              if (parseInt(row.LO1_ID) === parseInt(item.LO1_ID)) {
                  item['UM1_PURCHASE_ID'] = value['UM1_ID'];
                  item['UM1_PURCHASE_SIZE'] = parseInt(value['UM1_SIZE']);
                  item = this.checkPackSize(item);
              }
              return item;
          });
          this.locationMasterData = locationMasterData;
      }
  }

  umReceiptChange(value, row) {
      if (value.hasOwnProperty('UM1_ID')) {
          var locationMasterData = this.locationMasterData.map( item => {
              if (parseInt(row.LO1_ID) === parseInt(item.LO1_ID)) {
                  item['UM1_RECEIPT_ID'] = value['UM1_ID'];
                  item['UM1_RECEIPT_SIZE'] = parseInt(value['UM1_SIZE']);
                  item = this.checkPackSize(item);
              }
              return item;
          });
          if (parseInt(row['LO1_CENTER_FLAG'])) {
              locationMasterData = this.setUmReceipt(locationMasterData, row);
          }
          this.locationMasterData = locationMasterData;
      }
  }

  checkPackSize(item) {
      if (item['UM1_PURCHASE_ID'] === item['UM1_RECEIPT_ID']) {
          item['UM2_FACTOR'] = 1;
      } else if (item['UM1_PURCHASE_SIZE'] && item['UM1_RECEIPT_SIZE']) {
          item['UM2_FACTOR'] = item['UM1_PURCHASE_SIZE'] / item['UM1_RECEIPT_SIZE'];
      } else {
          item['UM2_FACTOR'] = 1;
      }
      return item;
  }

  openLinkCatalogPart() {
      this.router.navigate(['/master-catalogs/edit/' + this.record.MD0_ID]);
  }

  restrictInput(e){
    return e.charCode == 44 || e.charCode == 46 || (e.charCode >= 48 && e.charCode <= 57);
  }
}
