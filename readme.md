# MRQE - Merge Requests QueuE
This simple tool shows you all pending merge requests of your team members. It will show you all merge-requests that are:

- In state `open`
- Not yet approved by you
- Authored by people you choose to follow
- In order they were created
- Refreshed frequently in interval you decide (default is 1 minute)

## Installation
1. Clone this repository locally, copy & rename `config.json.dist` to `config.json` and edit the  file to your likings:

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
2. If you are using docker, you can simply use command:
```shell
make init
```

3. Next time you want to start this app just call:
```shell
make run
```

## Requirements & stack
- Docker & docker-compose
  
or
  
- PHP 8.0.1 fpm
- cURL
- nginx/apache server