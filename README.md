# Logging-error handling

![CI pipeline](https://github.com/szemul/logging-error-handling/actions/workflows/php.yml/badge.svg)
[![codecov](https://codecov.io/gh/szemul/logging-error-handling/branch/main/graph/badge.svg?token=KZJ13OF577)](https://codecov.io/gh/szemul/logging-error-handling)

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
