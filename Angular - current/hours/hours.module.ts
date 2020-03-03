import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { NgxPermissionsModule } from 'ngx-permissions';
import { SharedModule } from '../../modules/shared.module';
import { routes } from './hours.routing';
import { HoursComponent } from './hours.component';
import { MatAutocompleteModule } from '@angular/material';

@NgModule({
  imports: [
      RouterModule.forChild(routes),
      NgxPermissionsModule.forChild(),
      SharedModule,
      MatAutocompleteModule
  ],
    declarations: [
        HoursComponent
    ]
})
export class HoursModule { }