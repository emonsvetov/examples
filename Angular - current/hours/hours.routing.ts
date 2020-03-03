import { Routes } from '@angular/router';
import { NgxPermissionsGuard } from 'ngx-permissions';
import { permissions  } from './../../permissions';
import { AuthGuardService as AuthGuard } from './../../auth-guard.service';
import { _lang } from '../../pipes/lang';
import { HoursComponent } from './hours.component';

export const routes: Routes = [{
    path: '',
    component: HoursComponent,
    canActivate: [AuthGuard, NgxPermissionsGuard],
    data: {
        title: _lang('Hours'),
        permissions: {
            only: [permissions.HOURS_READ]
        }
    }
}];