# Really Simple Discoverability 2.0 JSON Binding

Really Simple Discoverability is a way to help client software find the services needed to read, edit, or "work with" web-based software.

The goal of this specification is simple. It allows client applications to find web-service APIs for a domain.

The purpose of this binding is to simplify the discovery process for client applications using named service protocols.

This specification diverts from the original XML-based specifications in three directions.

1. It adds information on the transport protocol.
2. It allows an attribute-free syntax.
3. It describes the discovery process

## Table of Contents

* [The Problem](#the-problem)
* [The RSD Approach](#the-rsd-approach)
* [Elements](#elements)
* [HTML Integration](#html-integration)
* [Server-based Discovery](#server-based-discovery)
* [Copyright & Disclaimer](#copyright--disclaimer)

## The Problem

> Communicating with services requires knowledge about the service whereabouts and settings.
> Specifically, discovering the "Path To Service" setting, but more generally where to point for services is a common issue.

From [RSD specification 1.0.0](https://github.com/danielberlinger/rsd).

Client applications may have more specific needs when connecting to services.
Some clients may require specific transport protocols, such as SOAP, XML-RPC, WebDAV, or REST.
Other clients have requirements on the supported protocol, such as versions or optional features.
If a service may support different versions of the same protocol, the client may want choose which version of the protocol it prefers.



### Why is there a need for extending the original RSD

The objective of this specifications to optimise automatic RSD generation in multiple data formats, such as JSON and YAML. Opposite to XML these formats define hierarchical data formats without additional attributes. Therefore, a direct transformation of the original specification to these formats is not possible.

## The RSD Approach

RSD provides a lightweight interface for web-service discovery. It assumes that clients have been implemented towards consuming a service. Thus, clients typically require a pointer to a specific service-api. Through RSD clients are able to determine the correct location to a named web-service API on a server.

The simplest RSD description consists of an engine link and a list of supported APIs.

### Difference to WSDL/UDDI

For SOAP Services the WSDL specification serves a similar purpose.
The main difference to WSDL/UDDI is RSD's openness regarding different service protocols.
While WDSL/UDDI allows to specify the entire service API and service behaviour, RSD simply provides pointers to individual services.
With RSD service settings are optional.

## Information Model

Each RSD consists of one service descriptor.

| Name | Required | Type |
| :--- | :--- | :--- |
| Service | Yes | ```Object``` |

### Service

A Service

| Name | Required | Type | Identifier |
| :--- | :--- | :--- | :--- |
| engineLink | Yes | ```URI``` | No |
| apis | Yes | ```List``` | No |
| engineName | No | ```String``` | No |
| engineId | No | ```String``` | No |
| homePageLink | No | ```URI``` | No |
| homePageIcon | No | ```URI``` | No |

### engineLink

The engineLink contains an URI, which points to the root of the engine. This link might be hosted on a different host or domain. The engineLink is mandatory for the RSD. However, the engineLink might be empty, but in this case all apiLinks

### apis

The apis contains a list of Links to API. Each API in the list can appear only once.

| Name | Required | Type |
| :--- | :--- | :--- |
| api | Yes | ```Object``` |


### engineName

Optional human readable name of the service engine. This name might be used for displaying service related information in an UI so users can identify the service.

### engineId

The engineId contains an optional identifier for a subdomain if the engine provides the same services to serveral separated domains. The engineId might be used globally for the entire engine or locally for one specific API.

If the engineId is specified both for the engine and an API, then the engineId used for the API always takes precedence.

### homePageLink

Contains an optional URI to the web-based UI of the engine. This URI might be on a different domain.

### homePageIcon

The homePageIcon is an optional pointer to a image file containing the UI icon that might be used to display next to the engine name to an user.

### api

| Name | Required | Type | Identifier |
| :--- | :--- | :--- | :--- |
| name | Yes | ```String``` | Yes |
| apiLink | Yes | ```URI``` | No |
| apiVersion | No | ```Complex``` | No |
| engineId | No | ```String```| No |
| preferred | No | ```Boolean``` | No |
| transport | No | ```Enumeration``` | No |
| docs | No | ```URI``` | No |
| description | No | ```URI``` | No |
| notes | No | ```String``` | No |
| settings | No | ```Object``` | No |


### name

The required apiName is a public name for the service. Within an RSD each apiName can appear only once.

### apiLink

The required mandatory apiLink contains a link to the api. The apiLink can be relative to the engineLink or contain an absolute URI to an API. An URI is considered as absolute if it contains :// with no preceeding colon (':') or slash ('/').

### apiVersion

The optional apiVersion can contain either a ```String``` or a ```List``` of ```Strings``` referring to the supported API versions.

### preferred

The optional preferred value indicated wether a API should be preferred over alternative APIs. If preferred is missing ```No``` or ```false``` are assumed.

If an API is preferred then ```Yes``` or ```true``` should be used.

### transport

The optional transport contains the supported web-service transport types. Use transport for indicating multiple supported web-service types.

Possible values are:

* SOAP
* REST
* XML-RPC
* JSON-RPC
* Web-Form

If transport is missing, Web-Form is assumed. Web-Form refers to services using the conventional url-encoded GET/POST parameter passing to CGI scripts.

### docs

The docs contains an URI to the official specification supported by the API. This can be used as reference for API implementations.

### description

The description is an optional reference to a machine readable service description. If present, the description **must** contain a valid URI to a service specification in WSDL.

### notes

notes can contain a human readable text containing information about the functions of the API. This information can be displayed to used in an UI.

### settings

| Name | Required | Type |
| :--- | :--- | :--- |
| setting | No | ```Object``` |

### setting

A setting is a key-value pair. Each setting contains additional information for the client that is required for connecting to a API.

| Name | Required | Type | Identifier |
| :--- | :--- | :--- | :--- |
| name | Yes | ```String``` | Yes |
| value | Yes | ```Complex``` | No |

## Service Discovery via relative Links

Each RSD contains a homePageLink, an engine link, and API links. Normally the RSD information is located alongside the homePageLink. The service engine and the API Links can be located on different hosts as the system layout requires it.

RSD 2.0 allows relative linking. If an homePageLink is set, and the engineLink is not an absolute URI, then it is assumed to be relative to the homePageLink.

If API Links are not defined as absolute URIs, then they are considered to be relative to the engineLink.

Link resolving **must** only be performed on relative links. Absolute Links **must not** get expanded.

If relative links are expanded, then the less significant parts of the URI need to end in a slash ('/').

Note, that for relative linking each link **must** resolve into a valid URI.

### Example Link resolving

Case 1: all absolute URIs

homePageLink: ```http://example.com```
engineLink: ```http://service.example.com```
apiLink: ```http://api.example.com/api/```

* The api can be accessed directly via ```http://api.example.com/api/```.

Case 2: relative apiLink

homePageLink: ```http://example.com```
engineLink: ```http://service.example.com```
apiLink: ```api/```

* The apiLink needs to get expanded using the engineLink.

* The engineLink does not end with a slash, therefore it needs to be expanded.

* The api can be accessed via ```http://service.example.com/api/```

Case 3: relative engineLink and relative apiLink

homePageLink: ```http://example.com```

engineLink: ```service/```

apiLink: ```api/```

* The apiLink needs to get expanded using the engineLink.

* The engineLink ends in a slash, no expansion required

* The engineLink is relative needs expansion via the homePageLink.

* The homePageLink does not end with a slash, therefore it needs to be expanded.

* The api can be accessed via ```http://example.com/service/api/```

## Language Bindings

RSD can be mapped onto different data formats, including but not limited to XML, JSON, and YAML.

### XML Binding

There are two possible XML Bindings. The attribute binding or the hierarchical binding.

All RSD XML documents have an ```<rsd>``` root element. The rsd element contains a version and a namespace definition. The version for documents following this specification is always ```2.0```. The namespace definition is ```http://github.com/rsd-spec/rsd```.

In both bindings the information model's identifiers are always mapped to XML attributes using the information model's name as attribute name and the setting as attribute value.


#### Attribute XML Binding

The attribute binding allows a transitional path from RSD 1.0 to RSD 2.0 the following mappings from the information model are made. Under RSD 2.0 all RSD version 1.0 documents remain valid, but new features can be used.

```xml
<?xml version="1.0" ?>
<rsd version="2.0" xmlns="http://github.com/rsd-spec/rsd" >
  <service>
    <engineName>Blog Munging CMS</engineName>
      <engineLink>http://www.blogmunging.com/ </engineLink>
      <homePageLink>http://www.userdomain.com/ </homePageLink>
      <homePageIcon>http://www.userdomain.com/favico.png </homePageIcon>
      <apis>
        <api name="MetaWeblog" preferred="true" apiLink="http://example.com/xml/rpc/url" engineId="123abc" />
        <api name="Blogger" preferred="false" apiLink="http://example.com/xml/rpc/url" engineId="123abc" />
        <api name="MetaWiki" preferred="false" apiLink="http://example.com/some/other/url" engineId="123abc" />
        <api name="Antville" preferred="false" apiLink="http://example.com/yet/another/url" engineId="123abc" />
        <api name="Conversant" preferred="false" apiLink="xml/rpc/url">
          <docs>http://www.conversant.com/docs/api/</docs>
          <notes>Additional explanation here.</notes>
          <transport>REST</transport>
          <transport>SOAP</transport>
          <settings>
            <setting name="service-specific-setting">a value</setting>
            <setting name="another-setting">another value</setting>
          </settings>
        </api>
      </apis>
   </service>
</rsd>
```

In the attribute XML binding blogID might be used as a synonym for engineId in the information model. If both blogID and engineId are present the value provided in blogID **must** be ignored.

For legacy reasons the API ```docs``` and ```notes``` may appear as part of the API ```settings```.

The following example shows a RSD in attribute binding using legacy mappings.

```xml
<?xml version="1.0" ?>
<rsd version="2.0" xmlns="http://github.com/rsd-spec/rsd" >
  <service>
    <engineName>Blog Munging CMS</engineName>
    <engineLink>http://www.blogmunging.com/ </engineLink>
    <homePageLink>http://www.userdomain.com/ </homePageLink>
    <apis>
      <api name="MetaWeblog" preferred="true" apiLink="http://example.com/xml/rpc/url" blogID="123abc" />
      <api name="Blogger" preferred="false" apiLink="http://example.com/xml/rpc/url" blogID="123abc" />
      <api name="MetaWiki" preferred="false" apiLink="http://example.com/some/other/url" blogID="123abc" />
      <api name="Antville" preferred="false" apiLink="http://example.com/yet/another/url" blogID="123abc" />
      <api name="Conversant" preferred="false" apiLink="xml/rpc/url" blogID="">
         <transport>REST</transport>
         <transport>SOAP</transport>
         <settings>
           <docs>http://www.conversant.com/docs/api/ </docs>
           <notes>Additional explanation here.</notes>
           <setting name="service-specific-setting">a value</setting>
           <setting name="another-setting">another value</setting>
         </settings>
       </api>
    </apis>
  </service>
</rsd>
```

#### Hierarchical XML Binding

The Hierachical XML Binding creates a direct link to the other formats of the information model.

```xml
<?xml version="1.0" ?>
<rsd version="2.0" xmlns="http://github.com/rsd-spec/rsd" >
  <service>
    <engineName>Blog Munging CMS</engineName>
    <engineLink>http://www.blogmunging.com/</engineLink>
    <homePageLink>http://www.userdomain.com/</homePageLink>
    <homePageIcon>http://www.userdomain.com/favico.png</homePageIcon>
    <apis>
      <api name="MetaWeblog">
        <preferred>true</preferred>
        <apiLink>http://example.com/xml/rpc/url</apiLink> <engineId>123abc</engineId>
      </api>
      <api name="Conversant">
        <preferred>false</preferred>
        <apiLink>xml/rpc/url</apiLink>
        <docs>http://www.conversant.com/docs/api/</docs>
        <notes>Additional explanation here.</notes>
        <transport>REST</transport>
        <transport>SOAP</transport>
        <settings>
          <setting name="service-specific-setting">a value</setting>
          <setting name="another-setting">another value</setting>
        </settings>
      </api>
    </apis>
  </service>
</rsd>
```

The hierarchical XML binding has no counterpart in the original specification. Therefore, legacy mappings are forbidden.

### JSON Binding

The JSON binding replaces elements of the information model with their respective identifiers. Consequently, the parent element of the information model is missing in this representation.

```JSON
{
  "engineName": "Blog Munging CMS",
  "engineLink": "http://www.blogmunging.com/",
  "homePageLink": "http://www.userdomain.com/",
  "homePageIcon": "http://www.userdomain.com/favico.png",
  "apis": {
    "MetaWeblog": {
      "apiLink": "http://example.com/xml/rpc/url",
      "preferred": "true",
      "engineId": "123abc"
    }
    "Conversant": {
      "apiLink": "xml/rpc/url",
      "preferred": "false",
      "docs": "http://www.conversant.com/docs/api/",
      "notes": "Additional explanation here.",
      "transport": ["REST", "SOAP"],
      "settings": {
        "service-specific-setting": "a value",
        "another-setting": "another value"
      }
    }
  }
}
```

### YAML Binding

Similar to the JSON binding, the YAML binding mapps the Identifiers of the information model to the data keys. Consequently, the parent element of the information model is missing in this representation.

```YAML
service:
  engineName: Blog Munging CMS
  engineLink: http://www.blogmunging.com/
  homePageLink: http://www.userdomain.com/
  homePageIcon: http://www.userdomain.com/favico.png
  apis:
    MetaWeblog:
      apiLink: http://example.com/xml/rpc/url
      preferred: true
      engineId: 123abc
    Conversant:
      apiLink: xml/rpc/url
      preferred: false
      docs: http://www.conversant.com/docs/api/
      notes: Additional explanation here.
      transport:
        - REST
        - SOAP
      settings:
        service-specific-setting: a value
        another-setting: another value
```

## HTML Integration

An HTML page may include pointers to RSD files.

For the different formats, an individual link can be provided.

```HTML
<link rel="ServiceAPI" type="application/rsd+xml" title="RSD" href="http://example.com/rsd.xml">
<link rel="ServiceAPI" type="application/rsd+json" title="RSD" href="http://example.com/rsd.json">
<link rel="ServiceAPI" type="application/rsd+yaml" title="RSD" href="http://example.com/rsd.yaml">
```

## Host-based Integration

Alternativey, HTML link discovery, a host may also expose the RSD to external parties. A hosts may expose the supported RSDs using a ```services.txt``` file.

The ```services.txt``` file has the following format:

```
rsd-type; rsdURI
```

Each entry in this file needs to be stored in a separate line.

### Example services.txt

```
application/rsd+xml; http://example.com/rsd.xml
application/rsd+json; http://example.com/rsd.json
application/rsd+yaml; http://example.com/rsd.yaml
```

## References

[RSD Specification 1.0.0 using XML](https://github.com/danielberlinger/rsd)

## Copyright & Disclaimer

The authors: Christian Glahn (Blended Learning Center HTW Chur)

This document is released unter [Creative Commons 4.0 Attribution-ShareAlike](https://creativecommons.org/licenses/by-sa/4.0/legalcode).
