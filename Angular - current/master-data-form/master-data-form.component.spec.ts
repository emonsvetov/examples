import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MasterDataFormComponent } from './master-data-form.component';

describe('MasterDataFormComponent', () => {
  let component: MasterDataFormComponent;
  let fixture: ComponentFixture<MasterDataFormComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MasterDataFormComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MasterDataFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
