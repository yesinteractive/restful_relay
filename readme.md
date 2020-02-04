# Restful Relay
![alt text](https://raw.githubusercontent.com/yesinteractive/restful_relay/master/public/restrelay.png "FSL Fresh Squeezed Limonade PHP Microframework")


Restful Relay is a PHP Based Microservice that relays remote SFTP, SSH, SMTP commands through a RESTful interface. Microservice also can create JWT tokens as well as conduct request fanning. This utility works great in combination with API Gateway solution such as Kong (konghq.com), in a Kubernetes cluster, or a service mesh.

Documentation:
- [Installation and Deployment](#install)
- [SSH](#ssh)
- [SFTP](#sftp)
- [SMTP](#smtp)
- [JWT Creation](#jwt)
- [Request Fanning](#fan)
- Other protocols and utlities coming soon

---
<a name="install"> </a>
## Installation and Deployment Examples

See usage examples for Kubernetes, Kong for Kubernetes Ingress Controller, and docker-compose in the [examples directory folder.](https://github.com/yesinteractive/restful_relay/blob/master/examples)

### With Docker

Docker image is Alpine 3.11 based running PHP 7.3 on Apache. The containter exposes both ports 80 an 443 with a self signed certificated. If you wish to alter the container configuration, feel free to use the Dockerfile in this repo (https://github.com/yesinteractive/restful_relay/blob/master/Dockerfile). Otherwise, you can pull the latest image from DockerHub with the following command:
```
docker pull yesinteractive/restfulrelay
```
Typical basic usage:

```
docker run -it yesinteractive/restfulrelay
```

Typical usage in Dockerfile:

```
FROM yesinteractive/restfulrelay
RUN echo <your commands here>
```

<a name="ssh"> </a>
## SSH Command


Runs SSH command on remote server. Only currently supports username password authentication. 

**URL** : `/ssh`

**Method** : `POST`

**Parameters**

| Name        | Description |
| ----------- | ----------- |
| server      | Server to connect to (ie: localhost, somedomain.com, etc.) |
| user        | SSH Username  |
| pass        | SSH Password |
| command     | command - combine with && (ie: date && hostname)   |

**Auth required** : NO

### Success Response

**HTTPie Request** : ```http -f POST /ssh server=localhost user=myuser pass=mypass command='php -v'```

**Respones Code** : `200 OK`

```json
{
  "Response": {
    "Status": "Success",
    "Response": "PHP 5.6.36 (cli) (built: May 18 2018 04:51:01) \nCopyright (c) 1997-2016 The PHP Group\nZend Engine v2.6.0, Copyright (c) 1998-2016 Zend Technologies\n    with Zend OPcache v7.0.6-dev, Copyright (c) 1999-2016, by Zend Technologies\n",
    "Processing Time": "0.202658"
  }
}
```



<a name="sftp"> </a>
## SFTP Commands
Runs SSH command on remote server. Only currently supports username password authentication. 

### SFTP Get

**URL** : `/sftp/get`

**Method** : `POST`

**Parameters**

| Name        | Description |
| ----------- | ----------- |
| server      | Server to connect to (ie: localhost, somedomain.com, etc.) |
| user        | sftp Username  |
| pass        | sftp Password |
| filename    | filename to fetch   |
| dir         | OPTIONAL. directory of file. will use default logged in dir if not set  |

**Auth required** : NO

### Success Response

**HTTPie Request** : ```http -f POST /sftp/get server=localhost user=myuser pass=mypass filename='example.txt'```

**Respones Code** : `200 OK`

```json
{
  "Response": {
    "Status": "Success",
    "Response": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
    "Processing Time": "0.173307"
  }
}
```
### SFTP Put
Will return true if put sucessful, false if failed.

**URL** : `/sftp/put`

**Method** : `POST`

**Parameters**

| Name        | Description |
| ----------- | ----------- |
| server      | Server to connect to (ie: localhost, somedomain.com, etc.) |
| user        | sftp Username  |
| pass        | sftp Password |
| filename    | filename to save   |
| data        | data/text to save to file   |
| dir         | OPTIONAL. directory of file. will use default logged in dir if not set  |

**Auth required** : NO

### Success Response

**HTTPie Request** : ```http -f POST /sftp/put server=localhost user=myuser pass=mypass data='sample text' filename='example.txt'```

**Respones Code** : `200 OK`

```json
{
  "Response": {
    "Status": "Success",
    "Response": true,
    "Processing Time": "0.177481"
  }
}
```

---

<a name="smtp"> </a>
## SMTP - Send Mail


Sends mail via smtp. Only currently supports username password authentication.

**URL** : `/mailer`

**Method** : `POST`

**Parameters**

| Name        | Description |
| ----------- | ----------- |
| server      | SMtp Server to connect to (ie: localhost, somedomain.com, etc.) |
| user        | smtp Username  |
| pass        | smtp Password |
| port     | smtp server port   |
| from        | from email address - sender|
| from_name       | from name |
| to      | to email address - recipient  |
| subject        | message subject |
| body     | message body - text only currently   |


**Auth required** : NO

### Success Response

**HTTPie Request** : ```http -f POST /mailer server=localhost user=myuser pass=mypass port=587 from=sender@fqdn from_name=bob to=recipient@fqdn subject=hello body=world ```

**Respones Code** : `200 OK`

```json
{
  "Response": {
    "Status": "Message send successfully.",
    "Processing Time": "1.202658"
  }
}
```



<a name="jwt"> </a>
## Generate JWT

Generate a JWT token. Claims returned in token will consist of id and iat.

**URL** : `/jwt`

**Method** : `POST`

**Parameters**

| Name        | Description |
| ----------- | ----------- |
| key         | Signing Key |
| id          | Client ID   |

**Auth required** : NO

### Success Response

**HTTPie Request** : ```http -f POST /jwt key=mykey id=myuser```

**Respones Code** : `200 OK`

```json
{
  "Response": {
    "Status": "Success",
    "JWT": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6Im15dXNlciIsImlhdCI6MTU4MDAwMDU4MH0.aQluZvipTnaglibRBNa5Z-GUi_pp0meVIjhSzn4gPt8",
    "ID": "myuser",
    "Processing Time": "0.001395"
  }
}
```



<a name="fan"> </a>
## Request Fanning

Fans a single request into multiple requests and then conslidate responses to one JSON Response. Please note that 
this function assumes that the upstream responses from each URL specified will be JSON based. URL Requests are run concurrently for better speed rather than in a serial fashion which would cause greater latency.

**URL** : `/fan`

**Method** : `POST`

**Request Data Content Type Must Be application/json**

See example below. To perform an HTTP Get, simply list the URL. If you would like to perform a post, pass the post data in a group named 'post'. Example below is showing 3 requests - 2 GETs and 1 POST.

```json
[
  {
    "url": "http://dummy.restapiexample.com/api/v1/employees"
  },
  {
    "url": "http://dummy.restapiexample.com/api/v1/employees"
  },
  {
    "url": "http://dummy.restapiexample.com/api/v1/create",
    "post": {
      "appid": "YahooDemo",
      "output": "php",
      "context": "test"
    }
  }
]
```

**Auth required** : NO

### Success Response

**HTTPie Request** : ```http -f POST /fan <json pay load goes here> ```

**Respones Code** : `200 OK`

```json
[
  {
    "status": "success",
    "data": [
      {
        "id": "24",
        "employee_name": "Doris Wilder",
        "employee_salary": "85600",
        "employee_age": "23",
        "profile_image": ""
      }
    ]
  },
  {
    "status": "success",
    "data": {
      "name": null,
      "salary": null,
      "age": null,
      "id": 84
    }
  }
]
```




## Notes

* None.
