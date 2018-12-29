<?php
namespace LaravelAds\Console;

use Exception;
use Illuminate\Console\Command;
use Google\Auth\OAuth2;
use Google\Auth\CredentialsLoader;

class RefreshTokenCommand extends Command {

    /**
     * Console command signature
     *
     * @var string
     */
    protected $signature = 'laravelads:token:generate {--service=}';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Generate a refresh token for GoogleAds or BingAds';

    /**
     * Generate refresh token
     *
     */
    public function handle()
    {
        $service = strtolower($this->option('service'));

        switch($service)
        {
            case 'googleads' :
                $this->googleAdsRefresh();
            break;
            case 'bingads' :
                $this->bingAdsRefresh();
            break;
            default :
                $this->error("Error: --service option is required. (Use GoogleAds or BingAds)");
        }
    }

    /**
     * googleAdsRefresh
     *
     */
    protected function googleAdsRefresh()
    {
        $config = config('google-ads')['OAUTH2'] ?? [];

        $clientId = $config['clientId'];
        $clientSecret = $config['clientSecret'];

        $scopes = 'https://www.googleapis.com/auth/adwords';
        $authorizationUri = 'https://accounts.google.com/o/oauth2/v2/auth';
        $redirectUri = 'urn:ietf:wg:oauth:2.0:oob';

        $oauth2 = new OAuth2([
            'authorizationUri' => $authorizationUri,
            'redirectUri' => $redirectUri,
            'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'scope' => $scopes
        ]);

        // print first message
        $this->line(sprintf(
            "Please sign in to your AdWords account, and open following url:\n%s",
            $oauth2->buildFullAuthorizationUri([
                'access_type' => 'offline'
            ])
        ));

        // Retrieve token
        $accessToken = $this->ask('Insert your access token');

        // Fetch auth token
        try
        {
            $oauth2->setCode($accessToken);
            $authToken = $oauth2->fetchAuthToken();
        }
        catch (Exception $exception) {
            return $this->error($exception->getMessage());
        }

        if (!isset($authToken)) {
            return $this->error('Error fetching the refresh token');
        }

        $this->comment('Copy the refresh token and paste the value on ADWORDS_OAUTH2_REFRESH_TOKEN in your .env');

        // Print refresh token
        $this->line(sprintf(
            'Refresh token: "%s"',
            $authToken['refresh_token']
        ));
    }

    /**
     * googleAdsRefresh
     *
     */
    protected function bingAdsRefresh()
    {
        $this->line('Coming Soon.');
    }

}
