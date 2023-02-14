# DWH Query Doctrine Bundle

## About

The DWH uses GraphQL specification to retrieve the data.

This bundle uses and extends <a href="https://github.com/developmentwata/dwh-query-bundle">DwhQueryBundle</a> through registering the types according to a Doctrine definition.

# Installation

Via composer:

```sh
composer require watafactory/dwh-query-doctrine-bundle
```

# Development

In case you want to extend the library, you can set up a local installation using docker:

Run `docker-compose up -d` to run app. By default, the `docker-compose.yml` is used.

Run `docker-compose exec apache-php composer install` to install the vendors.

This docker-compose file is used for development. It starts the following container:

- php: it contains the application source code

# Documentation

This bundle automatically registers the types according the Doctrine definition of the entities and also create a resolver the automatically performs the query on this entity.

In order to use this bundle you need to change the schema_builder service in the dwh_query.yaml file:

```
dwh_query:
  schema_builder: 'dwh_query_doctrine.schema_builder'
```

## Defining what entities you want to use
In the dwh_query_doctrine.yaml file you can configure what Doctrine entities you can query using GraphQL:

```
dwh_query_doctrine:
  doctrine_entities:
    - { class: 'App\Domain\Filter\Model\Filter', manager: 'default' }
    - { class: 'App\Domain\Result\Model\ResultCompany', manager: 'company' }
    - { class: 'App\Domain\Result\Model\ResultManager', manager: 'company_manager' }
```
The bundle will automatically look for those entities and it will registry them as a GraphQL type.

## Defining the resolvers
The bundle automatically will register a resolver for each of the types configured. This resolver is defined in the class Wata\DwhQueryDoctrineBundle\Resolver\DoctrineQueryResolver and will use the Wata\DwhQueryDoctrineBundle\Query\QueryBuilder to perform the query using Doctrine.

## Defining the query
A basic query can be the following:

```
{
    Filter {
        id,
        name
    }
}
```
And the result:

```
{
    "data": {
        "Filter": [
            {
                "id": "bea703b2-b100-45d8-9acd-8bc6ef7d7e34",
                "name": "Filter 0"
            },
            {
                "id": "2a3d6308-513e-45a0-8d99-50b850f76be2",
                "name": "Filter 1"
            }
        ]
    }
}
```

A query with where and orderBy arguments:

```
{
    Filter (where: {name: {neq: "Filter 2"}}, orderBy: {name: DESC})
    {
        id,
        name
    }
}
```

A query retrieving entity associations:

```
{
    Filter (where: {name: {eq: "Filter 2"}})
    {
        id,
        name,
        items
        {
            id,
            name
        }
    }
}
```
And the results:

```
{
    "data": {
        "Filter": [{
            "id": "9015a795-1673-4162-9dda-ddf656459010",
            "name": "Filter 2",
            "items": [
            {
                "id": "3931ecc9-d96b-4dd8-b200-bce6a320527e",
                "name": "adipisci"
            },
            {
                "id": "527c8fd5-dc00-4c6a-8c31-ca1f25bf0fa1",
                "name": "dolore"
            }
            ]
        }]
    }
}
```

A query with groupBy arguments:

```
{
    ResultCompany(where: {scaleName: {eq: "Zeitdruck"}}, groupBy: ["scaleName"]){
        count__scaleName
    }
}
```
When you define the groupBy arguments you can retrieve these special fields:

* count__FIELDNAME: it performs a cound(FIELDNAME)
* sum__FIELDNAME: it performs a sum(FIELDNAME)
* avg__FIELDNAME: it performs a avg(FIELDNAME)



Operator in the where clause:

```
// between operator
Filter (where: {createdAt: {between: {from: "2000-01-01", to: "2023-01-02"}}})

// equal operator
Filter (where: {name: {eq: "Filter 2"}})

// not equal
Filter (where: {name: {neq: "Filter 2"}})
```

NOTE: if you want to use a field of an association in the where, orderBy or groupBy clauses you need to use association__fieldName like this:

```
Filter (where: {items__name: {eq: "XXX"}}){
```

## License

See [LICENSE](LICENSE).
