# Change Log
All notable changes to this project will be documented in this file.

## 0.6.1 - 2021-04-22
### Fixed:
- False positive `assertPromiseRejectsWith()`


## 0.6.0 - 2020-07-03
### Added:
 - New assertions `assertTrueAboutPromise()` and `assertFalseAboutPromise()` (#21 by @ace411) 

## 0.5.0 - 2020-05-22
### Added:
 - trait `AssertsPromise`

## 0.4.0 - 2020-03-09
### Changed:
 - `TestCase` is now abstract 
### Added:
 - `assertPromiseFulfillsWithInstanceOf()` to check class of the resolution value 

## 0.3.0 - 2020-03-08
### Updated:
 - Dependencies
 - Added types

## 0.2.2 - 2019-04-06
### Fixed:
 - use vendor PHPUnit when running TravisCI

## 0.2.1 - 2018-09-14
### Fixed:
 - assertions counter

## 0.2.0 - 2018-06-26
### Updated:
 - dependencies and php version changed to 7.2

## 0.1.5 - 2018-03-01
### Fixed:
 - wrong assertions count

## 0.1.4 - 2018-02-16
### Added:
 -  support for php7

## 0.1.3 - 2018-02-10 
### Fixed:
 - method names (replace verb `resolve` with `fulfill`)

## 0.1.3 - 2018-02-10 
### Fixed:
 - method names (replace verb `resolve` with `fulfill`)

## 0.1.2 - 2017-12-01
### Fixed:
 - increase assertions count when checking that promise resolves/rejects

## 0.1.1 - 2017-11-12
### Added:
 - some helpers to wait for promises

## 0.1.0 - 2017-11-10
- First tagged release

## 0.0.0 - 2017-11-08
- First initial commit 
