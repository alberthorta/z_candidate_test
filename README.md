# ZEELO Candidate Assignment test

## Candidate Info
**Albert Horta Llobet**

**+34 648 013 152**

**albert@horta.name**

## Quick SetUp

### Requirements:

1) VirtualBox (https://www.virtualbox.org/)
2) VirtualBox Extebsion Pack (https://www.virtualbox.org/wiki/Downloads)
2) Vagrant (https://www.vagrantup.com/)
3) Install some vagrant plugins
```vagrant plugin install vagrant-hostmanager```
4) Wake Up the machinne
```vagrant up```

_NOTES: You should be able to access the API through the url http://zeelo.local/api/v1/..._
    

## Implementation Details

This candidate assignment app has been developed in Symfony 4.1 trying to follow the DDD hexagonal framework architecture patterns. The app is using by default sqLite to avoid extra services being used on this small test app.

The configuration can be found at .env file ```DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db```
so the data.db is stored under ```var``` folder.

The folder structure is:
```
\- zeelo
     \- src
         \- Infrastructure (For the Infrastructure layer classes)
              such as:
                 - Controllers
                 - And EntityRepository Doctrine implementation
         \- Application (For the Application layer classes)
              such as:
                 - Item use cases
         \- Domain      (For the Domain related classes)
              such as:
                 - Entity (Item)
                 - EntityRepository interface
                 - Domain Exceptions
                 - Url ValueObjects
         \- DataFixtures (Seeder for the initial dataset)
              can be run using:
                  bin/console doctrine:fixtures:load
                 
```

### Implementation Notes (Infrastructure layer)

ItemController is the responsible of answering the API responses. It uses extensively the ```FOSRest``` library and the notation configuration to stablish the endpoints and to filter the input (this library it's also responsible of the JSON serializing).

Basically through the definition of ```QueryParams``` and ```RequestParams``` I'm setting stablishing the control over the validity of the inputs. On a real world app this will be a first barrier of control but much more will be required on the Domain side (through the logic on the Entity, the use cases and the ValueObjects, like what I did on the URL).

The ```ItemRepositoryDoctrine``` is the responsible of calling the specific Doctrine layer implementing the ```ItemRepository``` interface.

```


    ItemController   ---------->   ItemUseCases   --------->   Entity
    
         / \                            / \                        
          |                              |
          |     ____ (injects) _________/                                                 
          |    |                  
          |    |                                                      
          |    |
          |    |                                                     
          |    |
          |    |                        
                          
ItemRepositoryDoctrine.   <- - - - - (implements) - - - - -   ItemRepository
                                  
                                  
```

### Implementation Notes (Application layer)

On the application layer we have the use case class. That it's constructed receiving an ```ItemRepository``` implementation (Using the Dependency Inversion Principle) in order to avoid coupling with the Infrastructure layer ```ItemRepositoryDoctrine```.

Here we have the three use cases that we have to cover:

1) Item Creation
2) Item Listing
3) Item Requesting

### Implementation Notes (Domain layer)

The domain layer covers the Item Entity definition. This file also uses extensively the ORM @notation used to define the field names and their properties. By one hand it's a quick way to do it, but on the other hand we have a soft coupling here, because all this definition should belong to the infrastructure level (and I'm saying soft because in the end, we just have comments that are not eally affecting the class itself in the case we decided to change the persistance repository).

Here I'm also using the @notation for teh JMS Serializer, to indicate which groups of fields have to be serialized and how.

Here I also implemented the **ValueObjects** pattern to cover the bussines logic on the Url, so we can be sure that if something that's not a URL is sent to the Entity, the value object will throw an Exception that will be captured by the Controller (returning the exception) defined on ```/config/packages/fos_rest.yaml``` file.
 

## Running Tests

Just go to ```/vagrant/zeelo``` and run:

1) For unit tests ```bin/phpunit --testsuite unit```
2) For unit tests ```bin/phpunit --testsuite integration``` (obviously running the integration tests will make real modifications on the DB file)

## Final Throughts

If we wanted to implement the Command Query Responsibility Segregation pattern we will have to change a little bit the Item creation, because we should send a valid ID because by definition (maybe I'm wrong) the updates on the bus never return anything, so the return of the inserted instance will be quite impossible.

Usually the CQRS implementations use UUID generated from cliend side with a very very low chance of ID clash.
