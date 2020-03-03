import { Routes } from '@angular/router';
import { NgxPermissionsGuard } from 'ngx-permissions';

import { permissions } from './../../permissions';
import { CompaniesComponent } from './companies.component';
import { AuthGuardService as AuthGuard } from './../../auth-guard.service';
import { CompanyFormComponent } from './company-form/company-form.component';
import { _lang } from '../../pipes/lang';

export const routes: Routes = [{
    path: '',
    component: CompaniesComponent,
    canActivate: [AuthGuard, NgxPermissionsGuard],
    data: {
        title: _lang('Companies'),
        permissions: {
            only: [permissions.COMPANIES_READ]
        }
    }
},{
    path: 'add',
    component: CompanyFormComponent,
    canActivate: [AuthGuard, NgxPermissionsGuard],
    data: {
        title: _lang('Add'),
        permissions: {
            only: [permissions.COMPANIES_ADD]
        }
    }
},{
    path: 'edit/:id',
    component: CompanyFormComponent,
    canActivate: [AuthGuard, NgxPermissionsGuard],
    data: {
        title: _lang('#Edit#'),
        permissions: {
            only: [permissions.COMPANIES_EDIT]
        }
    }
},{
    path: 'copy/:id',
    component: CompanyFormComponent,
    canActivate: [AuthGuard, NgxPermissionsGuard],
    data: {
        title: _lang('Clone'),
        permissions: {
            only: [permissions.COMPANIES_ADD]
        }
    }
}];