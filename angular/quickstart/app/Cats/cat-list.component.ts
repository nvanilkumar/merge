// Import component decorator
import { Component, OnInit } from '@angular/core';

import { PetService } from '../pet.service'

import { Observable } from 'rxjs/Observable';

import { Pet } from '../pet';

@Component({
   moduleId: module.id,
   templateUrl: 'cat.component.html'
})
// Component class
export class CatListComponent implements OnInit {

  cats: Observable<Pet[]>;
  constructor(private petService: PetService) {

  }

  ngOnInit() {
    this.cats = this.petService.findPets('cat');
  }

    heroes = ['Windstorm', 'Bombasto', 'Magneta', 'Tornado'];
    addHero(newHero: string) {
    if (newHero) {
      this.heroes.push(newHero);
    }
  }

}
