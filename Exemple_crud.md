# Angular 21 + Symfony 8 CRUD avec MySQL

Ce document explique comment mettre en place un CRUD complet pour l'entité `Customers` avec **Symfony 8** et **Angular 21**, en utilisant une base MySQL.

---

## 1️⃣ Base de données (MySQL)

Table `customers` :

| Colonne      | Type         | Nullable |
| ------------ | ------------ | -------- |
| id           | int          | Non      |
| nombre       | varchar(45)  | Non      |
| surname_1    | varchar(45)  | Non      |
| surname_2    | varchar(45)  | Oui      |
| age          | int          | Non      |
| email        | varchar(122) | Non      |
| phone_number | varchar(17)  | Non      |

---

## 2️⃣ Symfony API

Assumons que tu as déjà ton entity `Customers` et le repository.

### Routes API recommandées

```yaml
# config/routes.yaml
customers:
  resource: "../src/Controller/CustomerController.php"
  type: annotation
```

### Exemple de Controller Symfony

```php
<?php

namespace App\Controller;

use App\Entity\Customers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/customers')]
class CustomerController extends AbstractController
{
    #[Route('', name: 'get_customers', methods: ['GET'])]
    public function index(EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $customers = $em->getRepository(Customers::class)->findAll();
        $json = $serializer->serialize($customers, 'json');
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'get_customer', methods: ['GET'])]
    public function show(Customers $customer, SerializerInterface $serializer): Response
    {
        $json = $serializer->serialize($customer, 'json');
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    #[Route('', name: 'create_customer', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);
        $customer = new Customers();
        $customer->setNombre($data['nombre'])
                 ->setSurname1($data['surname_1'])
                 ->setSurname2($data['surname_2'] ?? null)
                 ->setAge($data['age'])
                 ->setEmail($data['email'])
                 ->setPhoneNumber($data['phone_number']);

        $em->persist($customer);
        $em->flush();

        $json = $serializer->serialize($customer, 'json');
        return new Response($json, 201, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'update_customer', methods: ['PUT'])]
    public function update(Customers $customer, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        $customer->setNombre($data['nombre'])
                 ->setSurname1($data['surname_1'])
                 ->setSurname2($data['surname_2'] ?? null)
                 ->setAge($data['age'])
                 ->setEmail($data['email'])
                 ->setPhoneNumber($data['phone_number']);

        $em->flush();
        $json = $serializer->serialize($customer, 'json');
        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/{id}', name: 'delete_customer', methods: ['DELETE'])]
    public function delete(Customers $customer, EntityManagerInterface $em): Response
    {
        $em->remove($customer);
        $em->flush();
        return new Response(null, 204);
    }
}
```

---

## 3️⃣ Angular 21

### 3.1 Interface `Customer`

```ts
// src/app/models/customer.ts
export interface Customer {
  id?: number;
  nombre: string;
  surname1: string;
  surname2?: string;
  age: number;
  email: string;
  phoneNumber: string;
}
```

### 3.2 Service Angular

```ts
// src/app/services/customer.service.ts
import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable, map } from "rxjs";
import { Customer } from "../models/customer";

@Injectable({
  providedIn: "root",
})
export class CustomerService {
  private apiUrl = "http://localhost:8000/api/customers";

  constructor(private http: HttpClient) {}

  private mapCustomer(apiCustomer: any): Customer {
    return {
      id: apiCustomer.id,
      nombre: apiCustomer.nombre,
      surname1: apiCustomer.surname_1,
      surname2: apiCustomer.surname_2,
      age: apiCustomer.age,
      email: apiCustomer.email,
      phoneNumber: apiCustomer.phone_number,
    };
  }

  getCustomers(): Observable<Customer[]> {
    return this.http
      .get<any[]>(this.apiUrl)
      .pipe(map((arr) => arr.map(this.mapCustomer)));
  }

  getCustomer(id: number): Observable<Customer> {
    return this.http
      .get<any>(`${this.apiUrl}/${id}`)
      .pipe(map(this.mapCustomer));
  }

  createCustomer(customer: Customer): Observable<Customer> {
    const payload = {
      ...customer,
      surname_1: customer.surname1,
      surname_2: customer.surname2,
      phone_number: customer.phoneNumber,
    };
    return this.http
      .post<any>(this.apiUrl, payload)
      .pipe(map(this.mapCustomer));
  }

  updateCustomer(id: number, customer: Customer): Observable<Customer> {
    const payload = {
      ...customer,
      surname_1: customer.surname1,
      surname_2: customer.surname2,
      phone_number: customer.phoneNumber,
    };
    return this.http
      .put<any>(`${this.apiUrl}/${id}`, payload)
      .pipe(map(this.mapCustomer));
  }

  deleteCustomer(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
}
```

---

### 3.3 Exemple de composant CRUD

```ts
// src/app/components/customer/customer.component.ts
import { Component, OnInit } from "@angular/core";
import { CustomerService } from "../../services/customer.service";
import { Customer } from "../../models/customer";

@Component({
  selector: "app-customer",
  templateUrl: "./customer.component.html",
})
export class CustomerComponent implements OnInit {
  customers: Customer[] = [];
  newCustomer: Customer = {
    nombre: "",
    surname1: "",
    age: 0,
    email: "",
    phoneNumber: "",
  };

  constructor(private customerService: CustomerService) {}

  ngOnInit(): void {
    this.loadCustomers();
  }

  loadCustomers() {
    this.customerService
      .getCustomers()
      .subscribe((data) => (this.customers = data));
  }

  createCustomer() {
    this.customerService.createCustomer(this.newCustomer).subscribe(() => {
      this.newCustomer = {
        nombre: "",
        surname1: "",
        age: 0,
        email: "",
        phoneNumber: "",
      };
      this.loadCustomers();
    });
  }

  deleteCustomer(id: number) {
    this.customerService
      .deleteCustomer(id)
      .subscribe(() => this.loadCustomers());
  }
}
```

### 3.4 Template HTML

```html
<!-- src/app/components/customer/customer.component.html -->
<h2>Liste des Clients</h2>
<ul>
  <li *ngFor="let c of customers">
    {{c.nombre}} {{c.surname1}} - {{c.email}} - {{c.phoneNumber}}
    <button (click)="deleteCustomer(c.id!)">Supprimer</button>
  </li>
</ul>

<h2>Ajouter un Client</h2>
<form (ngSubmit)="createCustomer()">
  <input
    type="text"
    placeholder="Nombre"
    [(ngModel)]="newCustomer.nombre"
    name="nombre"
    required
  />
  <input
    type="text"
    placeholder="Surname1"
    [(ngModel)]="newCustomer.surname1"
    name="surname1"
    required
  />
  <input
    type="text"
    placeholder="Surname2"
    [(ngModel)]="newCustomer.surname2"
    name="surname2"
  />
  <input
    type="number"
    placeholder="Age"
    [(ngModel)]="newCustomer.age"
    name="age"
    required
  />
  <input
    type="email"
    placeholder="Email"
    [(ngModel)]="newCustomer.email"
    name="email"
    required
  />
  <input
    type="text"
    placeholder="Phone Number"
    [(ngModel)]="newCustomer.phoneNumber"
    name="phoneNumber"
    required
  />
  <button type="submit">Ajouter</button>
</form>
```

---

### 4️⃣ Notes importantes

1. Assure-toi que **CORS** est activé sur Symfony si Angular tourne sur un autre port (ex: 4200).
2. Angular 21 utilise `@angular/forms` pour `ngModel` et `ReactiveFormsModule` si tu veux les formulaires réactifs.
3. Mapping camelCase → snake_case dans le service Angular pour garder ton code propre.

---

Ceci te donne un **CRUD Angular 21 complet connecté à ton API Symfony et ta base MySQL**, prêt à l’emploi.

---

Veux‑tu que je te fasse maintenant **la version Dockerfile + docker-compose complète pour Angular + Symfony + MySQL** pour que tout fonctionne ensemble sans souci ?
