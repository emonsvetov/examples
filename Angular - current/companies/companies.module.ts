import { NgModule } from '@angular/core';
import { NgxPermissionsModule } from 'ngx-permissions';
import { RouterModule } from '@angular/router';

import { CompanyService } from '../../services/company.service';
import { CompaniesComponent } from './companies.component';
import { routes } from './companies.routing';
import { CompanyFormComponent } from './company-form/company-form.component';
import { SharedModule } from '../../modules/shared.module';
import { CurrencyModule } from '../../components/currency/currency.module';

@NgModule({
  exports: [RouterModule],
  imports: [
      RouterModule.forChild(routes),
      NgxPermissionsModule.forChild(),
      SharedModule,
      CurrencyModule
  ],
  providers: [
      CompanyService
  ],
  declarations: [
      CompaniesComponent,
      CompanyFormComponent
  ]
})
export class CompaniesModule { }
