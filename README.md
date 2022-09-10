# Logging-error handling

![CI pipeline](https://github.com/szemul/logging-error-handling/actions/workflows/php.yml/badge.svg)
[![codecov](https://codecov.io/gh/szemul/logging-error-handling/branch/main/graph/badge.svg?token=KZJ13OF577)](https://codecov.io/gh/szemul/logging-error-handling)

## Deprecation notice

This package has been deprecated and has been split to 3 packages:
szemul/logging-error-handling-context - Only contains the contexts
szemul/sentry-error-handler - Only contains the sentry error handler
szemul/monolog-logging-context - Only contains the context support for monolog

### Reasons for the deprecation

This package couldn't require the correct versions of sentry and monolog, users of the package needed to require it 
themselves. With monolog version 3 the JsonFormatter's signatures changed and the package couldn't clearly say what 
version it supports without requiring all users to install both sentry and monolog even if it's not needed otherwise.

The new package organisation allows us to require sentry and monolog in the supported versions only in the more 
targeted packages.

### Migration to the new packages

Version 1 of the new packages is just a copy of the classes in this repository, however the base namespaces are 
different. So require the packages you need and update the namespaces. No other changes are needed for version 1.

# Original readme

Provides commonly usable error handlers and logging helpers and a common context to manage contextual data to be
injected into errors and log messages.

The following error handlers are available:
* SentryErrorHandler - for sending rich errors to Sentry

The following formatters are available for monolog:
* ContextAwareJsonFormatter - for sending rich log messages in JSON format via monolog

## Context

The context is useful for storing values used to enrich logs and errors. The context class supports switching contexts.
When adding a new context, the existing values are preserved, and you can switch back to any previous context and
recover the state (switching back drops any changes in any newer context).

## Traits

There are some helper traits provided to help with the bootstrapping process and configure the error handler and the
JSON formatter.
