# Restful Relay

Restful Relay is a PHP Based Microservice that relays remote FTP, SSH, SMTP, POP, IMAP, DNS, Telnet commands through a RESTful interface. Microservice also can create JWT tokens as well as conduct request fanning. This utility works great in combination with API Gateway solutions such as Kong (konghq.com). 

Documentation:
- [SSH](#ssh)
- [JWT Creation](#jwt)
- Other protocols and utlities coming soon

---

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

## Notes

* None.
