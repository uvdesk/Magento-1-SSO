# Magento-1-SSO
Magento 1 SSO based customer login system for third-party applications

# Installation
Magento SSO module installation is very easy. To install, please follow the steps as given below:

1. Make sure compiler is disabled
2. Unzip the respective magento_sso_login.zip and then move the contents of "src" folder into magento root directory
3. Reindex all index data from admin panel and then clear the cache

# User Guide
Once the module has been installed successfully:

1. Login to your magento admin panel
2. Open the SSO Management Panel by going to "SSO" -> "Add/Manage SSO"
3. In the SSO Management Panel, click on "Add SSO" to create new single sign-on credentials

Once you have created your SSO credentials, use it to allow your customers to login to your third-party website.

{LOGIN_URL}:                      {STORE_URL}/sso/sso/index?client_id={CLIENT_ID}&redirect_uri={REDIRECT_URL}
{JWT_TOKEN_URL}:                  {STORE_URL}/sso/sso/response?auth_code={auth_code}&client_id={CLIENT_ID}
{CREDENTIAL_VERIFICATION_URL}:    {STORE_URL}/sso/sso/verify?secret_key={SECRET_KEY}&client_id={CLIENT_ID}

Login Flow:
Save the sso credentials somewhere on your third-party website as these should not be accessible to front users. To login your customers from your third-party website:
1. Redirect them to your store's {LOGIN_URL} where they will be prompted to authorize access to your integration
2. After they successfully authorize the integration to access their data, they will be redirected to the provided {REDIRECT_URL} with an auth_code appended in the redirect url
3. Retrieve the auth_code from the url and send a post request to {JWT_TOKEN_URL} to retrieve a JWT Token
4. Process the JWT Token to verify the token and to retrieve the customer's details

# Support
Find us our support policy - https://store.webkul.com/support.html/

# Refund
Find us our refund policy - https://store.webkul.com/refund-policy.html/