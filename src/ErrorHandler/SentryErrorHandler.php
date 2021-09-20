<?php
declare(strict_types=1);

namespace Szemul\LoggingErrorHandling\ErrorHandler;

use ErrorException;
use Sentry\State\HubInterface;
use Sentry\State\Scope;
use Szemul\ErrorHandler\Handler\ErrorHandlerInterface;
use Szemul\LoggingErrorHandling\Context\ContextInterface;
use Szemul\LoggingErrorHandling\Helper\SentryArrayHelper;
use Throwable;

class SentryErrorHandler implements ErrorHandlerInterface
{
    public function __construct(
        protected HubInterface $sentryClientHub,
        protected ContextInterface $context,
        protected SentryArrayHelper $contextHelper,
        protected ?string $errorViewerBaseUrl = null,
    ) {
    }

    /** @return array<string,mixed>|null */
    public function __debugInfo(): ?array
    {
        return [
            'sentryClientHub'    => '** Instance of ' . get_class($this->sentryClientHub),
            'context'            => $this->context,
            'contextHelper'      => $this->contextHelper,
            'errorViewerBaseUrl' => $this->errorViewerBaseUrl,
        ];
    }

    public function handleError(
        int $errorLevel,
        string $message,
        string $file,
        int $line,
        string $errorId,
        bool $isErrorFatal,
        array $backTrace = [],
    ): void {
        $this->sendExceptionToSentry(new ErrorException($message, 0, $errorLevel, $file, $line), $errorId);
    }

    public function handleException(Throwable $exception, string $errorId): void
    {
        $this->sendExceptionToSentry($exception, $errorId);
    }

    public function handleShutdown(int $errorLevel, string $message, string $file, int $line, string $errorId): void
    {
        $this->sendExceptionToSentry(new ErrorException($message, 0, $errorLevel, $file, $line), $errorId);
    }

    protected function sendExceptionToSentry(Throwable $exception, string $errorId): void
    {
        $this->sentryClientHub->configureScope(
            // Not using type hinting as Scope is final and doesn't implement an interface so it'd make this untestable
            function ($scope) use ($errorId) {
                /** @var Scope $scope */
                $scope->clear();

                $scope->setTag('error_id', $errorId);

                $errorContext = ['id' => $errorId];

                if (!empty($this->errorViewerBaseUrl)) {
                    $errorContext['link'] = $this->errorViewerBaseUrl . $errorId;
                }

                $scope->setContext('error', $errorContext);

                $this->addGlobalContextDataToScope($scope);
            },
        );

        $this->sentryClientHub->captureException($exception);
    }

    /**
     * @param Scope $scope Not typehinted to help with testing
     */
    protected function addGlobalContextDataToScope($scope): void
    {
        $user = $this->context->getErrorHandlerUser();

        if (!empty($user)) {
            $scope->setUser($user);
        }

        foreach ($this->context->getErrorHandlerTags() as $key => $value) {
            $scope->setTag($key, $value);
        }

        foreach ($this->context->getErrorHandlerContexts() as $key => $value) {
            $scope->setContext($key, $value);
        }

        foreach ($this->context->getErrorHandlerExtras() as $key => $value) {
            $scope->setExtra($key, $value);
        }
    }
}
