<?php

namespace Botble\Base\Commands;

use Botble\Base\Supports\Core;
use Botble\Base\Commands\Traits\ValidateCommandInput;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

#[AsCommand('cms:license:activate', 'Activate license')]
class ActivateLicenseCommand extends Command
{
    use ValidateCommandInput;

    public function __construct(protected Core $coreApi)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $buyer = $this->askWithValidate('Enter username', 'required|min:2|max:60');

        if (filter_var($buyer, FILTER_VALIDATE_URL)) {
            $buyer = explode('/', $buyer);
            $username = end($buyer);

            $this->components->error('Envato username must not a URL. Please try with username "' . $username . '"!');

            return self::FAILURE;
        }

        $purchaseCode = $this->askWithValidate('Enter purchase code', 'required|min:19|max:36');

        try {
            $result = $this->coreApi->activateLicense($purchaseCode, $buyer);

            $resetLicense = false;
            if (! $result['status']) {
                if (str_contains($result['message'], 'License is already active')) {
                    $this->coreApi->deactivateLicense($purchaseCode, $buyer);

                    $result = $this->coreApi->activateLicense($purchaseCode, $buyer);

                    if (! $result['status']) {
                        $this->components->info($result['message']);

                        return self::FAILURE;
                    }

                    $resetLicense = true;
                } else {
                    $this->components->info($result['message']);

                    return self::FAILURE;
                }
            }

            setting()
                ->set(['licensed_to' => $buyer])
                ->save();

            if ($resetLicense) {
                $this->components->error($result['message'] . ' Your license on the previous domain has been revoked!');

                return self::FAILURE;
            }

            $this->components->info($result['message']);

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
