<!--suppress HtmlDeprecatedAttribute -->
<h1 align="center">
  <br>
  <a href="https://www.sphereon.com"><img src="https://sphereon.com/content/themes/sphereon/assets/img/logo.svg" alt="Sphereon" width="400"></a>
  <br>Craft CMS - OpenID for Verifiable Credentials plugin 
  <br>
</h1>

**warning: Please note this plugin in still in very early development stage. Do not use for authentication purposes in
production settings!**

# Craft CMS OID4VC support

This Craft CMS plugin adds support for the new decentralized identity protocols. commonly known as OpenID for Verifiable
Credentials. This allows you to create Authentication flows without having to keep a user database. You will relly on
claims issued and signed by 3rd party Issuers, begin presented to you by the user/holder.

## OID4VCI specification

This is a new [set of specifications](https://openid.net/openid4vc/) by the [OpenID Foundation](https://openid.net/),
that enables peer to peer authentication ([SIOPv2](https://openid.net/specs/openid-connect-self-issued-v2-1_0.html)),
Credential Issuance ([OID4VCI](https://openid.net/specs/openid-4-verifiable-credential-issuance-1_0.html)) and
Credential Presentation/Verification ([OID4VP](https://openid.net/specs/openid-4-verifiable-presentations-1_0.html)).

The plugin needs to have a SIOPv2 and OpenID4VP SSI-Agent available, which will be handling the authentication process
for you, as well as verifying that the received Verifiable Credentials are conforming to the Presentation Definition.
You can create your own Agent with our [SSI-SDK](https://github.com/Sphereon-Opensource/ssi-sdk), by looking at
the [example/demo](https://github.com/Sphereon-Opensource/SIOPv2-OpenID4VP-example), or by using the publicly hosted
Demo by Sphereon at https://ssi-backend.sphereon.com.

## SSI Wallet required

A user needs to have a SIOPv2/OID4VP capable wallet in order to authenticate. You can use
our [SSI Wallet](https://github.com/Sphereon-OpenSource/ssi-mobile-wallet) which is also available in the stores for
instance.

## Demo agent

If you want to test the plugin, without installing your own agent, you can use our wallet, together with our publicly
hosted demo agent. You would need to configure the plugin in the CP with the following settings (plugin settings to be
found at https://your.craft.site/admin/settings/plugins/sphereon-oid4vc):

- Presentation Definition ID: sphereon
- SSI Agent Base URL: https://ssi-backend.sphereon.com
- Auth Success Redirect URL: any page you like

The Presentation Definition called 'sphereon' above accepts any self-asserted Verifiable Credential created by
the [Sphereon Wallet](https://github.com/Sphereon-OpenSource/ssi-mobile-wallet). Given this Credential will be created
during the onboarding process, you will always have this Credential available when using the Sphereon Wallet.

# Developers

## Controllers

### SIOP Controller

The SIOP Controller is responsible for the SIOPv2 and OID4VP requests/responses

#### Generating new Authentication Request Data

The `init` action gets a fresh session and QR code from the agent. You can call the `init` action with a GET request:

`https://craft.ddev.site/actions/sphereon-oid4vc/siop/init`

```json
{
  "correlationId": "adc4853f-21b9-4e5c-be07-8577a9f23fdb",
  "definitionId": "sphereon",
  "authRequestURI": "openid-vc://?request_uri=https%3A%2F%2Fssi-backend.sphereon.com%2Fsiop%2Fdefinitions%2Fsphereon%2Fauth-requests%2Fadc4853f-21b9-4e5c-be07-8577a9f23fdb",
  "authStatusURI": "https://ssi-backend.sphereon.com/webapp/auth-status",
  "qr": "data:image/png;base64,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"
}
```

The QR value could be used directly in an Image (a Twig extension is available). The QR value contains the data from
the `authRequestURI` property. You can show the `authRequestURI` value as link, for same-device flows where the wallet
is being hosted on a mobile device for instance. The QR code is handy for cross-device flows, where the website is
opened on a computer and the authentication process happens using the mobile phone.

You will need the `correlationId` and `definitionId` to poll for the status of the authentication session.

#### Getting the Authentication Status

The `status` action returns the current authentication status from the agent. It can only be accesed using a POST with
the above `correlationId` and `definitionId` as JSON payload in the body.

The authentication response has a `status` field, which can have the following values:

- `created`: A new session/QR has been created by the Relying Party
- `sent`: A Wallet has retrieved/accessed the Authentication Request URI, and thus should now be in the process of
  authenticating
- `received`: A very short lived status that indicates the Agent received a response from the wallet. Typically one of
  the next 2 `status` values follow immediately
- `verified`: The holder could satisfy the authentication request and presentation definition. A `payload` property
  containing the vp_token and id_token and `verifiedData` property containing claims are now also part of the response.
- `error`: An error occurred

example:`https://craft.ddev.site/actions/sphereon-oid4vc/siop/status`

Body:

```json
{
  "correlationId": "adc4853f-21b9-4e5c-be07-8577a9f23fdb",
  "definitionId": "sphereon"
}
```

Response:

```json
{
  "status": "verified",
  "correlationId": "096633f7-3e87-43a6-91b6-74a5c6fe30ac",
  "definitionId": "sphereon",
  "lastUpdated": 1686801103736,
  "payload": {
    "expires_in": 300,
    "state": "096633f7-3e87-43a6-91b6-74a5c6fe30ac",
    "id_token": "eyJhbGciOiJFZERTQSIsImtpZCI6ImRpZDprZXk6ejZNa2VzM2txN25iZ0d6YjE3d2lRRnZ2VVhEb0tUaDJiU1U2VjdIS2N0Y1o0QU05I3o2TWtlczNrcTduYmdHemIxN3dpUUZ2dlVYRG9LVGgyYlNVNlY3SEtjdGNaNEFNOSIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL3NlbGYtaXNzdWVkLm1lL3YyL29wZW5pZC12YyIsImF1ZCI6ImRpZDp3ZWI6ZGJjMjAyMy50ZXN0LnNwaGVyZW9uLmNvbSIsImlhdCI6MTY4Njc0MTEwMSwiZXhwIjoxNjg2ODAxNDAxLCJzdWIiOiJkaWQ6a2V5Ono2TWtlczNrcTduYmdHemIxN3dpUUZ2dlVYRG9LVGgyYlNVNlY3SEtjdGNaNEFNOSIsIm5vbmNlIjoiNDYxZjE4Y2ItZmU4MC00ZWQxLTlkZDEtMzMxNmY5MDYwYjlkIiwiX3ZwX3Rva2VuIjp7InByZXNlbnRhdGlvbl9zdWJtaXNzaW9uIjp7ImlkIjoiZkJUR2tSTjd6Z2RQU1Z0b1hRelV6IiwiZGVmaW5pdGlvbl9pZCI6Ijk0NDllMmRiLTc5MWYtNDA3Yy1iMDg2LWMyMWNjNjc3ZDJlMCIsImRlc2NyaXB0b3JfbWFwIjpbeyJpZCI6IlNwaGVyZW9uV2FsbGV0SWQiLCJmb3JtYXQiOiJqd3RfdmMiLCJwYXRoIjoiJC52ZXJpZmlhYmxlQ3JlZGVudGlhbFswXSJ9XX19fQ.0JGVx5Dq-o2tIdgcuWgqvQnAVVYCbnxf0gyQqKkCPycyzliB6BhNNHL-gVZXkSLzDYrvC1AcRzXEJySV3xa1DA",
    "vp_token": "eyJraWQiOiJkaWQ6a2V5Ono2TWtlczNrcTduYmdHemIxN3dpUUZ2dlVYRG9LVGgyYlNVNlY3SEtjdGNaNEFNOSN6Nk1rZXMza3E3bmJnR3piMTd3aVFGdnZVWERvS1RoMmJTVTZWN0hLY3RjWjRBTTkiLCJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJ2cCI6eyJAY29udGV4dCI6WyJodHRwczovL3d3dy53My5vcmcvMjAxOC9jcmVkZW50aWFscy92MSIsImh0dHBzOi8vaWRlbnRpdHkuZm91bmRhdGlvbi9wcmVzZW50YXRpb24tZXhjaGFuZ2Uvc3VibWlzc2lvbi92MSJdLCJ0eXBlIjpbIlZlcmlmaWFibGVQcmVzZW50YXRpb24iLCJQcmVzZW50YXRpb25TdWJtaXNzaW9uIl0sInZlcmlmaWFibGVDcmVkZW50aWFsIjpbImV5SnJhV1FpT2lKa2FXUTZhMlY1T25vMlRXdGxjek5yY1RkdVltZEhlbUl4TjNkcFVVWjJkbFZZUkc5TFZHZ3lZbE5WTmxZM1NFdGpkR05hTkVGTk9TTjZOazFyWlhNemEzRTNibUpuUjNwaU1UZDNhVkZHZG5aVldFUnZTMVJvTW1KVFZUWldOMGhMWTNSaldqUkJUVGtpTENKaGJHY2lPaUpGWkVSVFFTSXNJblI1Y0NJNklrcFhWQ0o5LmV5SjJZeUk2ZXlKQVkyOXVkR1Y0ZENJNld5Sm9kSFJ3Y3pvdkwzZDNkeTUzTXk1dmNtY3ZNakF4T0M5amNtVmtaVzUwYVdGc2N5OTJNU0lzSW1oMGRIQnpPaTh2YzNCb1pYSmxiMjR0YjNCbGJuTnZkWEpqWlM1bmFYUm9kV0l1YVc4dmMzTnBMVzF2WW1sc1pTMTNZV3hzWlhRdlkyOXVkR1Y0ZEM5emNHaGxjbVZ2YmkxM1lXeHNaWFF0YVdSbGJuUnBkSGt0ZGpFdWFuTnZibXhrSWwwc0luUjVjR1VpT2xzaVZtVnlhV1pwWVdKc1pVTnlaV1JsYm5ScFlXd2lMQ0pUY0dobGNtVnZibGRoYkd4bGRFbGtaVzUwYVhSNVEzSmxaR1Z1ZEdsaGJDSmRMQ0pqY21Wa1pXNTBhV0ZzVTNWaWFtVmpkQ0k2ZXlKbWFYSnpkRTVoYldVaU9pSk9hV1ZzY3lBaUxDSnNZWE4wVG1GdFpTSTZJa3RzYjIxd0lpd2laVzFoYVd4QlpHUnlaWE56SWpvaWJtdHNiMjF3UUhOd2FHVnlaVzl1TG1OdmJTSjlmU3dpYzNWaUlqb2laR2xrT210bGVUcDZOazFyWlhNemEzRTNibUpuUjNwaU1UZDNhVkZHZG5aVldFUnZTMVJvTW1KVFZUWldOMGhMWTNSaldqUkJUVGtpTENKcWRHa2lPaUoxY200NmRYVnBaRG8yTXpFd1pEUTJOaTFtTjJZMUxUUmpPRGt0T1dGa1ptSmlPREZtWlRZNFpUUmxOaUlzSW01aVppSTZNVFk0TmpVeE9UTTRNeXdpYVhOeklqb2laR2xrT210bGVUcDZOazFyWlhNemEzRTNibUpuUjNwaU1UZDNhVkZHZG5aVldFUnZTMVJvTW1KVFZUWldOMGhMWTNSaldqUkJUVGtpZlEuRERRQ2o0SVphd0Z6RDVWQzh4TjNnbGphVlZhMjN5cVdleWdkTDZ0dkNZTUpvTlQ4OU1UVmJKMUZneWhIWXE0eFdiOFFIbUtvd0xNTEZPTWtkZEItQUEiXX0sInByZXNlbnRhdGlvbl9zdWJtaXNzaW9uIjp7ImlkIjoiZkJUR2tSTjd6Z2RQU1Z0b1hRelV6IiwiZGVmaW5pdGlvbl9pZCI6Ijk0NDllMmRiLTc5MWYtNDA3Yy1iMDg2LWMyMWNjNjc3ZDJlMCIsImRlc2NyaXB0b3JfbWFwIjpbeyJpZCI6IlNwaGVyZW9uV2FsbGV0SWQiLCJmb3JtYXQiOiJqd3RfdmMiLCJwYXRoIjoiJC52ZXJpZmlhYmxlQ3JlZGVudGlhbFswXSJ9XX0sIm5iZiI6MTY4NjgwMTEwMCwiaXNzIjoiZGlkOmtleTp6Nk1rZXMza3E3bmJnR3piMTd3aVFGdnZVWERvS1RoMmJTVTZWN0hLY3RjWjRBTTkifQ.lOOohx-qwhIT-CKIqe3mVcFcnAEP4AVyHtqIw9fZyXrK9vt2hUYZcsROjJu26lEs-3zMIWjDGeW0WjJ8DQ4yBQ"
  },
  "verifiedData": {
    "firstName": "Jane",
    "lastName": "Doe",
    "email": "jane@example.com"
  }
}
```

## Services

### SIOP service

This service is responsible for getting the initial authentication request session and subsequently for getting the
authentication status.

### QR Code service

This service generates the QR codes for you. It returns them as data URIs, which means you can use the output directly
in browsers/Image tags

### Settings service

This service is responsible for the settings.

# License

This integration is MIT licensed.