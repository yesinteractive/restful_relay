#Coming Soon

FTP, SSH, Request Fan, SMTP, POP, IMAP, DNS, Telnet

# Generate JWT

Generate a JWT token. Claims returned in token will consist of id and iat.

**URL** : `/jwt`

**Method** : `POST`

**Parameters**

| Name        | Description |
| ----------- | ----------- |
| key         | Signing Key |
| id          | Client ID   |

**Auth required** : NO

## Success Response

**HTTPie Request** : ```http -f POST example.org key=mykey id=myuser```

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
