# Ochen K.

## Code Samples

* ASH Micro-site [(live site)](https://www.seattlecca.org/ash)
    * **Client**: Seattle Cancer Care Alliance
    * **Project Description**: The American Society of Hematology (ASH) hosts an annual conference in Seattle, 
    Washington. The Seattle Cancer Care Alliance (SCCA) has significant representation at the ASH conference. 
    To highlight and promote its offerings at the conference, SCCA created a micro-site presenting all SCCA
    speakers, abstracts, and trials, as well as regular news stories and blog posts during the event.
    * **My role**: Sole developer. Backend and frontend.
    * **[Code Sample](https://github.com/ochenk/code_samples/scca_ash)**
        * One of the technical requirements of the ASH micro-site project was to keep the development work separate enough 
    that it could be disabled or replicated easily once the conference was over, so all the backend code is contained 
    within this one module and all the frontend work is within a corresponding custom theme.
        * **Things of mild interest**: 
            * Backend: Theme switching. 
            * Frontend (as seen on the [live site](https://www.seattlecca.org/ash)): Page-level dynamic searching, contextual sticky headers.

* State Firearm Laws [(live site)](https://statefirearmlaws.org/)
    * **Client**: Boston University School of Public Health
    * **Project Description**: A small Drupal 8 site that collects and displays current and historical state firearm laws.
    The maps are created using the [D3.js](https://d3js.org/) data visualization library. 
    * **My role**: Senior back-end developer. Map creation. Data migration. 
    * **[Code Sample](https://github.com/ochenk/code_samples/sfal_nat_map)**
        * This module manages the map on the [home page](https://statefirearmlaws.org/) and on the [national
        data](https://statefirearmlaws.org/national-data) page of the [State Firearm Laws](https://statefirearmlaws.org/) site. 
        Includes the map itself and the corresponding api, forms, display, and config.
        * **Things of mild interest**:
            * Backend: json api, config via admin forms, custom form ajax (non-native form ajax methods).
            * Frontend: D3.js. Merging and cross-mapping data sources.

* Healthy People 2020 [(live site)](https://healthypeople.gov/) 
    * **Client**: The Office of Disease Prevention and Health Promotion - a division of the U.S. Department of Health 
    and Human Services.
    * **Project Description**: The Office of Disease Prevention and Health Promotion (ODPHP) sets the metrics
    and benchmarks to measure the health of the country. The Healthy People site is the warehouse for 
    the collected data and related material.
    * **My role**: Senior Developer and Architect.
    * **[Code Sample](https://github.com/ochenk/code_samples#ch_datasearch)**
        * This manages the entire [Data Search](https://www.healthypeople.gov/2020/data-search/Search-the-Data) section of the
        [Health People](https://www.healthypeople.gov) site. (It's a Drupal 7 site, in case that's of any maintenance value.)
        * **Things of mild interest**:
            * Backend: custom cache bin, static and dynamic queries.
            * Front: behavior and non-behavior triggers, dynamic hashes, nested ajax.
        
                
### Other Projects
* [Peace First](https://www.peacefirst.org/)
    * **Description**: A platform that supports young peacemakers by providing tools, resources, and funding for peacemaking projects. 
    * **My role**: Senior Developer.

* [The Southern Theater](https://southerntheater.org/)
    * **Description**: A custom Laravel application that includes:
        * A public-facing website with online ticket sales
        * A backend ticketing administration system
        * A point-of-sale/box office system
        * Custom reporting of shows, performances, sales, and donations
        * Integrations with Stripe, MailChimp, and AWS
    * **My role**: Sole developer. Frontend and backend. Project manager.

* [@TCStreetFood](https://twitter.com/tcstreetfood)
    * **Description**: A Twitter bot that tracks all food trucks in the Minneapolis/St.Paul metro area and posts daily 
    tweets and maps on truck locations.
    Includes Google Maps integration for dynamic maps. This Twitter account has come to be considered the canonical 
    source for food truck locations in the area, and is regularly referenced in local media and the community.
    Originally a vanilla PHP application. Rebuilt in Laravel in 2017.
    * (*Note: Because this is in Minnesota, the feed gets pretty quiet in the winter.*)
    * **My role**: Owner, creator, maintainer, spokesperson. 
    
* [PicturePublicHealth](https://picturepublichealth.com/)
    * **Description**: A stock photo site targeting health-related needs. A Drupal 7 Commerce site. 
    * **My role**: Senior Developer and Architect.
    
* [ochenk.com](http://www.ochenk.com/)
    * My personal, mostly-non-dev, project site. 
    
