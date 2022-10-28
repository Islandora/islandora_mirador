![Islandora](https://cloud.githubusercontent.com/assets/2371345/25624809/f95b0972-2f30-11e7-8992-a8f135402cdc.png)

# Mirador viewer integration for Islandora

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://github.com/islandora/islandora/actions/workflows/build-2.x.yml/badge.svg)](https://github.com/Islandora/islandora/actions)
[![Contribution Guidelines](http://img.shields.io/badge/CONTRIBUTING-Guidelines-blue.svg)](./CONTRIBUTING.md)
[![LICENSE](https://img.shields.io/badge/license-GPLv2-blue.svg?style=flat-square)](./LICENSE)
[![codecov](https://codecov.io/gh/Islandora/islandora/branch/2.x/graph/badge.svg)](https://codecov.io/gh/Islandora/islandora)

## Introduction

This is a Drupal module that wraps around the Mirador image viewer.
It does not strictly require Islandora, however it depends on an [IIIF Manifest](https://iiif.io/api/presentation/2.0/),
Islandora generates these manifests but they can also come from
third-party sources.

## Installation

```bash
$ composer require islandora/islandora_mirador
$ drush en islandora_mirador
```

### Upgrading from Islandora Defaults

This module was formerly distributed with Islandora Defaults. If you are upgrading
you may need to clear Drupal's cache and re-start your web server to resolve
a Plugin Not Found error.

### Local library

The module can use either an instance of Mirador Integration hosted
on the web or deployed locally. If you have a local build, put it in
your webroot at libraries/mirador/dist/main.js.

## Usage

The module provides a view mode that can be selected as a display hint when editing an Islandora Repository Item object.

See the documentation for [Islandora IIIF](https://islandora.github.io/documentation/user-documentation/iiif/) for how to set up the content that powers the viewer.

This module also provides a Block that can be included in custom displays.

## Configuration

The configuration page is located at /admin/config/media/mirador.

YOu can select if the library is deployed locally or set the remote
library's location.

You can also enable particular plugins. You need to know if the
plugins are included in the particular build of Mirador Integration.

You can set the URL pattern to retrieve the IIIF manifest for a piece of content.

## Documentation

Further documentation for IIIF (International Image Interoperability Framework) is available on the [Islandora 8 documentation site](https://islandora.github.io/documentation/user-documentation/iiif/).

## Troubleshooting/Issues

Having problems? Solved a problem? Join the Islandora [communication channels](https://www.islandora.ca/community#channels-of-communication) to post questions and share solutions:

* [Islandora Mailing List (Google Group)](https://groups.google.com/g/islandora)


* If you would like to contribute or have questions, please get involved by attending our weekly [Tech Call](https://github.com/Islandora/islandora-community/wiki/Weekly-Open-Tech-Call), held virtually via Zoom **every Wednesday** at [**1:00pm Eastern Time US**](https://dateful.com/convert/est-edt-eastern-time?t=13). Anyone is welcome to join and ask questions! The Zoom link can be found in the meeting minutes [here](https://github.com/Islandora/islandora-community/wiki/Weekly-Open-Tech-Call).

If you would like to contribute code to the project, you need to be covered by an Islandora Foundation [Contributor License Agreement](https://github.com/Islandora/islandora-community/wiki/Onboarding-Checklist#contributor-license-agreements) or [Corporate Contributor License Agreement](https://github.com/Islandora/islandora-community/wiki/Onboarding-Checklist#contributor-license-agreements). Please see the [Contributor License Agreements](https://github.com/Islandora/islandora-community/wiki/Contributor-License-Agreements) page on the islandora-community wiki for more information.
## License

[GPLv2](http://www.gnu.org/licenses/gpl-2.0.txt)
