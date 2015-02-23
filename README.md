## hyperRail

Introducing the new iRail.be. This is an application that transforms and uses data from the existing iRail API, which you can find [on GitHub](https://github.com/irail/api).

## Core concepts

### Content negotiation

#### Language

When a page is requested, the language preference is delegated from either the URL (using the GET parameter 'lang') or via accept headers. Accept headers take preference over any parameters.

#### Content-type based on accept headers

iRail doesn't use a classic API on another URL. There's no 'irail.be/api', instead you specify in the **accept headers** what kind of resource you'd like to request. By default, when visiting with a browser, you'll get a webpage that can be used by end-users.

You can also ask the application (iRail.be) for other kinds of resources. For example, you can ask for a JSON version of the data that the routeplanner serves.

In order to ask the API to serve you JSON (and not just the plain HTML that you get when you visit irail.be in your browser), send a request to `irail.be/route` but specify the requested content-type in your accept headers, e.g. `application/ld+json`.

### Hypermedia interface

Data returned through the API (when specifically requesting `application/ld+json`) should be structured and contains information about other possible paths (interaction) and contexts. Following the HATEOAS principle: 

> A REST client needs no prior knowledge about how to interact with any particular application or server beyond a generic understanding of hypermedia.

### Data dumps

Data dumps and the data that is used (and will be used and queryable in the future) are available at <a href="http://archive.irail.be">http://archive.irail.be</a>.