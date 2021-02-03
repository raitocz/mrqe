# MRQE - Merge Requests QueuE
This simple tool shows you all pending merge requests of your team members. It will show you all merge-requests that are:

- In state `open`
- Not yet approved by you
- Authored by people you choose to follow
- In order they were created
- Refreshed frequently in interval you decide (default is 1 minute)

## Installation
Clone this repository locally and edit the `config.json` file to your likings:

- **myUsername**: Your GitLab username, usually with dot between name & surname
- **followedUsers**: GitLab usernames of your team memebers which requests you want to see
- **refreshIntervalSeconds**: Number of seconds in which the list is updated
- **personalAccessToken**: Your GitLab access token (https://gitlab.carvago.com/-/profile/personal_access_tokens)

example configuration:
```json
{
  "myUsername": "martin.jagr",
  "followedUsers": [
    "martin.cechura",
    "ondrej.rehak"
  ],
  "refreshIntervalSeconds": 60,
  "personalAccessToken": "EWZr3D49RfAkEtOkENtaY"
}
```

After you set your preferences you can simply use command:
```shell
make run
```

## Requirements & stack
This app requires both docker & docker-compose to be installed but can be run without it using locally installed:
- PHP 8.0.1 fpm
- cURL
- nginx/apache server