// Imports
import { Component, OnInit } from '@angular/core';
import { PetService } from '../pet.service'
import { Observable } from 'rxjs/Observable';
import { Pet } from '../pet';

@Component({
  template: `
    <h2>Dogs</h2>
    <p>List of dogs</p>
    <ul class="demo-list-icon mdl-list">
      <li class="mdl-list__item" *ngFor="let dog of dogs | async">
        <span class="mdl-list__item-primary-content">
            <i class="material-icons mdl-list__item-icon">pets</i>
            <a [routerLink]="['/dogs', dog.id.$t]">{{dog.name.$t}}</a>
        </span>
      </li>
    </ul>
    `
})
// Component class implementing OnInit
export class DogListComponent implements OnInit {
  // Private property for binding
  dogs: Observable<Pet[]>;
  constructor(private petService: PetService) {

  }

  // Load data ones componet is ready
  ngOnInit() {
    // Pass retreived pets to the property
    this.dogs = this.petService.findPets('dog');
  }
}
