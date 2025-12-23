# 🧠 Architecture

La aplicación se implementa siguiendo Domain-Driven Design (DDD) con una arquitectura hexagonal (Ports & Adapters).
El objetivo principal es:

* Aislar el dominio del framework
* Permitir evolución del sistema sin refactors costosos
* Facilitar testing, versionado de APIs y escalabilidad

## 📦 Uso de la arquitectura por comando

1. Para tener una configuracion base ejecuta este comando, para crear una estructura base de Hexagonal + DDD
```bash
php artisan make:ddd {context} {modul}
```

2. Definiciones
- {context} → nombre del Bounded Context (ej: Lms)
- {module} → nombre del agregado o módulo (ej: Course)
- -r → opción para solo crear carpetas & archivos para cache/Redis
- -m → opción para solo crear carpetas & archivos para messaging/RabbitMQ


## 🏗️ Estructura de carpetas

- Dentro de src/ se encuentra la arquitectura hexagonal. Ejemplo: src/lms/course

```
├── app/                    # Framework (mínimo)
├── bootstrap/              # Providers
│   └── providers.php       
├── routes/
│   └── api.php             # Carga las rutas de los módulos
├── src/                    # Dominio y arquitectura
│   ├── lms/
│   │   ├── course/
│   │   │   ├── domain/
│   │   │   ├── application/
│   │   │   └── infrastructure/
│   │   ├── student/
│   │   └── enrollment/
│   ├── billing/
│   └── auth/
└── tests/
```

- Cada subcarpeta:
 * Tiene reglas propias
 * Puede evolucionar de forma independiente
 * Puede convertirse en microservicio en el futuro

- Este directorio contiene todo el código de negocio organizado por:
 * Core
 * Casos de uso
 * Adaptadores


## 🔵 Domain Layer (Core)
```
Domain/
├── Contracts/         # Contratos (interfaces)
├── Aggregates/        # Agregados raíz (Course, Student)
├── Entities/          # Entidades internas del agregado
├── ValueObjects/      # Objetos inmutables (Email, Title)
├── Repositories/      # Puertos de salida (interfaces)
├── Services/          # Servicios de dominio
├── Events/            # Eventos de dominio
└── Exceptions/        # Excepciones del dominio
```
Uso de cada carpeta

* Contracts/ Contiene los contratos (interfaces).
* Aggregates/ Contiene los agregados raíz. Son el punto de entrada al dominio.
* Entities/ Entidades que viven dentro del agregado y no se usan directamente desde fuera.
* ValueObjects/ Objetos inmutables que representan conceptos del dominio.
* Repositories/ Interfaces que definen cómo el dominio persiste o recupera datos.
* Services/ Lógica de dominio que no pertenece a una entidad concreta.
* Events/ Hechos importantes del dominio.
* Exceptions/ Errores propios del negocio.

⚠️ Esta capa no conoce Laravel.

## 🟡 Application Layer (Use Cases)
```
application/
├── Contracts/                               # Contratos (interfaces)
├── Commands/                                # Comandos
│   ├── Create{NameModule}Commands.php
│   ├── Update{NameModule}Commands.php
├── DTO/                                     # Data Transfer Objects
├── Listeners/                               # Listeners
├── Query/                                   # Queries
├── UseCases/                                # Casos de uso
│   ├── Create{NameModule}UseCase.php
│   ├── Update{NameModule}UseCase.php
│   ├── GetAll{NameModule}UseCase.php
│   ├── Get{NameModule}UseCase.php
│   └── Delete{NameModule}UseCase.php
```

Responsabilidad:

* Orquestar el dominio
* Ejecutar reglas de negocio
* Coordinar repositorios y servicios

Qué NO debe contener:

* ❌ HTTP
* ❌ Validaciones de framework
* ❌ SQL / Eloquent

## 🟢 Infrastructure Layer (Adapters)

- Este sera la estructura de carpetas para la parte de Adapters. Por defecto Se utilizara Eloquent, las demas excluirlas.
- Si se especifica Redis se utilizara la carpeta de Cache, Se agrega en Providers el servicio de Cache, sino excluirla.
- Si se especifica RabbitMQ se utilizara la carpeta de Messaging, Se agrega en Providers el servicio de Messaging, sino excluirla.
```
Infrastructure/
├── Http/
│   ├── Controllers/                                    # Controladores de la API
│   │    └── Create{NameModule}{Method}Controller.php
│   ├── Requests/                                       # Validadores de la API
│   │    └── Create{NameModule}{Method}Request.php
│   └── Routes/                                         # Rutas de la API
│        └── api.php
│   └── Filters/                                         # Filtros de la API
│        └── {NameModule}QueryFilter.php
│   
├── Persistence/
│   ├── Eloquent/                                       # Separacion por tecnologia
│   │   ├── Models/                                         # Modelos de la API
│   │   │   └── {NameModule}Model.php
│   │   ├── Mappers/                                        # Mappers de la API
│   │   │   └── {NameModule}Mapper.php
│   │   └── Repositories/                                   # Repositories de la API
│   │       └── {NameModule}Repository.php
│   ├── Sql/ (La carpeta Models se reemplaza por Queries)
│   ├── Mongo/ (La carpeta Models se reemplaza por Documents)
│   └── EventStore/ # solo si usas event sourcing (Solo tendra: Steaming/, Repositories/ y Projections/)
│   
├── Cache/                                            # Llama la logica del cache -> Redis
│   ├── Contracts/
│   │   └── CacheStoreInterface.php                     # Contrato de la logica del cache
│   ├── Redis/
│   │   └── RedisCacheStore.php                         # Implementacion de la logica del cache
│   └── Decorators/
│       └── CacheStoreDecorator.php                     # Decorador de la logica del cache
│
├── Messaging/                                        # Llama la logica del messaging -> RabbitMQ
│   ├── Contracts/
│   │   └── EventPublisherInterface.php                 # Contrato de la logica del messaging
│   └── RabbitMQ/
│       ├── Contracts/
│       │   └── EventPublisherInterface.php             # Contrato de la logica del messaging
│       ├── Publisher/
│       │   └── RabbitMqEventPublisher.php              # Implementacion de la logica del messaging
│       ├── Mappers/
│       │   └── RabbitMqEventMapper.php                 # Mapper de la logica del messaging
│       └── Connection/
│           └── RabbitMqConnection.php                  # Implementacion de la logica del messaging
│
├── Database/                                        # Llama la logica del messaging -> RabbitMQ
│   ├── Factories/
│   │   └── {NameModule}Factory.php              
│   └── Seeders/
│       └── {NameModule}Seeder.php               
│
├── Providers/                                        
│   ├── EventServiceProvider.php                       # Escuchas de Eventos de terceros 
│   ├── RepositoryServiceProvider.php                  # Enlace a Redis
│   ├── MessagingServiceProvider.php                   # Enlace a RabbitMQ
│   ├── PersistenceServiceProvider.php                 # Escuchas de la base de datos actual Eloquent
```

## 🔁 Flujo completo de ejecución
```
HTTP Request
   ↓
Route (Infrastructure)
   ↓
Controller (Infrastructure / Adapter In)
   ↓
Request / Validator (Infrastructure)
   ↓
DTO / Command (Application boundary)
   ↓
Use Case (Application)
   ↓
Domain (Aggregates, Services, Rules)
   ↓
Repository Interface (Domain Port)
   ↓
Repository Implementation (Infrastructure / Adapter Out)
   ↓
Persistence (Eloquent / DB)
```
