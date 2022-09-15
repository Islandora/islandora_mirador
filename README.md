# Mirador viewer integration for ![Islandora](https://cloud.githubusercontent.com/assets/2371345/25624809/f95b0972-2f30-11e7-8992-a8f135402cdc.png) Islandora

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://github.com/islandora/islandora/actions/workflows/build-2.x.yml/badge.svg)](https://github.com/Islandora/islandora/actions)
[![Contribution Guidelines](http://img.shields.io/badge/CONTRIBUTING-Guidelines-blue.svg)](./CONTRIBUTING.md)
[![LICENSE](https://img.shields.io/badge/license-GPLv2-blue.svg?style=flat-square)](./LICENSE)
[![codecov](https://codecov.io/gh/Islandora/islandora/branch/2.x/graph/badge.svg)](https://codecov.io/gh/Islandora/islandora)

## Introduction

## Installation

```bash
$ composer require islandora/islandora_mirador
$ drush en islandora_mirador
```

## Usage

The module provides a view mode that can be selected as a display hint when editing an Islandora Repository Item object.

See the documentation for Islandora IIIF for how to set up the content that powers the vviewer.

This module also provides a Block that can be included in custom displays.

## Configuration

The configuration page is located at /admin/config/media/mirador.

You can set the URL pattern to retrieve the IIIF manifest for a piece of content.

## Documentation

Further documentation for this module is available on the [Islandora 8 documentation site](https://islandora.github.io/documentation/).

## Troubleshooting/Issues

Having problems or solved a problem? Check out the Islandora google groups for a solution.

* [Islandora Group](https://groups.google.com/forum/?hl=en&fromgroups#!forum/islandora)

* If you would like to contribute, please get involved by attending our weekly [Tech Call](https://github.com/Islandora/islandora-community/wiki/Weekly-Open-Tech-Call). We love to hear from you!

If you would like to contribute code to the project, you need to be covered by an Islandora Foundation [Contributor License Agreement](https://github.com/Islandora/islandora-community/wiki/Onboarding-Checklist#contributor-license-agreements) or [Corporate Contributor License Agreement](https://github.com/Islandora/islandora-community/wiki/Onboarding-Checklist#contributor-license-agreements). Please see the [Contributor License Agreements](https://github.com/Islandora/islandora-community/wiki/Contributor-License-Agreements) page on the islandora-community wiki for more information.
## License

[GPLv2](http://www.gnu.org/licenses/gpl-2.0.txt)

