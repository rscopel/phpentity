## Introduction ##

The goal is to create a standardized error handling framework that allows for multi-language support.

## Details ##

  * errors will be incorporated with CoreSeed
  * error numbers will be associated with a name-space
  * namespace errors require range (eg. 100-200)
  * framework will track error usage and ranges must be unique
  * for development usage only; turned off on live deployments
  * reserved error ranges for core components (CoreSeed, CoreDatabase) with ability for future growth