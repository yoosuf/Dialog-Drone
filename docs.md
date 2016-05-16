
## Create Stadium
### POST `/stadiums`

*Request*

    {"name" : "SSC Grounds"}

*Response*

    {
       "name":"SCC Grounds",
       "updated_at":"2015-10-09 04:15:52",
       "created_at":"2015-10-09 04:15:52",
       "id":1
    }

## List Stadiums
### GET `/stadiums`

*Response*
    
    {
        "data" : [
            {
               "name":"SCC Grounds",
               "updated_at":"2015-10-09 04:15:52",
               "created_at":"2015-10-09 04:15:52",
               "id":1
            },
            {
               "name":"Premadasa Grounds",
               "updated_at":"2015-10-09 04:15:52",
               "created_at":"2015-10-09 04:15:52",
               "id":2
            }
        ]
    }

## Get Stadium
### GET `/stadiums/{id}`

*Response*

    {
       "name":"SCC Grounds",
       "updated_at":"2015-10-09 04:15:52",
       "created_at":"2015-10-09 04:15:52",
       "id":1
    }

## List Stands
### GET `/stadiums/{id}/stands`

*Response*

    {
       "data":[
          {
             "id":62,
             "stadium_id":30,
             "name":"A",
             "lat":0,
             "lng":0,
             "altitude":0,
             "yaw_default":0,
             "yaw_start":10,
             "yaw_end":10,
             "gimbal_pitch":0,
             "type":"standard",
             "created_at":"2015-10-09 04:47:55",
             "updated_at":"2015-10-09 04:47:55"
          },
          {
             "id":63,
             "stadium_id":30,
             "name":"B",
             "lat":0,
             "lng":0,
             "altitude":0,
             "yaw_default":0,
             "yaw_start":10,
             "yaw_end":10,
             "gimbal_pitch":0,
             "type":"standard",
             "created_at":"2015-10-09 04:47:55",
             "updated_at":"2015-10-09 04:47:55"
          }
       ]
    }

## Create Stand
### POST `/stadiums/{id}/stands`

*Request*

    {
        "name" : "A",
        "type" : "standard"
    }

*Response*

    {
       "name":"A",
       "type":"standard",
       "stadium_id":55,
       "updated_at":"2015-10-09 05:01:37",
       "created_at":"2015-10-09 05:01:37",
       "id":93
    }

## Get Stand
### GET `/stadiums/{id}/stands/{stadium_id}`

*Response*
    
    {
        "id":63,
        "stadium_id":30,
        "name":"B",
        "lat":0,
        "lng":0,
        "altitude":0,
        "yaw_default":0,
        "yaw_start":10,
        "yaw_end":10,
        "gimbal_pitch":0,
        "type":"standard",
        "created_at":"2015-10-09 04:47:55",
        "updated_at":"2015-10-09 04:47:55"
    }

## Update Stand
### POST `/stadiums/{id}/stands/{stadium_id}`

*Request*

    {
       "lat":89.45454,
       "lng":78.56565,
       "altitude":10,
       "yaw_default":0,
       "yaw_start":10,
       "yaw_end":10,
       "gimbal_pitch":0
    }

*Response*

    {
       "name":"A",
       "lat":89.45454,
       "lng":78.56565,
       "altitude":10,
       "yaw_default":0,
       "yaw_start":10,
       "yaw_end":10,
       "gimbal_pitch":0,
       "type":"standard",
       "stadium_id":59,
       "updated_at":"2015-10-09 05:04:37",
       "created_at":"2015-10-09 05:04:37",
       "id":98
    }

## Delete Stand
### DELETE `/stadiums/{id}/stands/{stadium_id}`

All the scores asscoiated with the stand will be removed.

*Response*
    
    {
       "success" : true
    }

## Start Game Session
### POST `/matches/{match_id}/start_session`

Clearing scores table

## Submit Scores
### POST `/matches/{match_id}/scores`

Should be sent with user auth token.

*Request*
    
    {
        "stand_id" : 1,
        "score" : 10
    }


## Finish Game Session
### POST `/matches/{match_id}/finish_session`

Finish a session, will return the json object sent to the drone.


## Sign Up User
### POST `/auth/signup`

*Request*
    
    {
        "name": "sahanlak",
        "login": "sahanlak",
        "email": "sahan@sahanz.com",
        "password": "sahan123",
        "mobile": "772754541",
        "profile_image": ""
    }


*Response*
    
    {
        "token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlz"
    }


## Request Mobile Verification
### POST `/auth/validate_mobile`

A code will be generated and sent to user.

## Validate Mobile Verification Code
### POST `/auth/validate_mobile`

*Request*
    
    {
        "code" : 12345
    }


*Response*
    
    {
        "success" : true
    }

Code being the verification code generated for mobile verification.

## Get User Token
### POST `/auth/signin_token`

*Request*
    
    {
        "login" : "sahanlak",
        "password" : "sahan123"
    }


*Response*
    
    {
        "token" : "n78ashdahd8ahsd78ha8shda87d"
    }

Use the token in other endpoints by including in Authorization header.

## Get Current User Details
### GET `/me`


*Request Headers*
    `Authorization: Bearer {user_token}`


*Response*
    
    {
        "name": "sahanlak",
        "login": "sahanlak",
        "email": "sahan@sahanz.com",
        "password": "sahan123",
        "mobile": "772754541",
        "profile_image": ""
    }

## Update Current User Details
### POST `/me`

All the fields are optional, if a field is sent, it will be updated.

*Request Headers*
    `Authorization: Bearer {user_token}`

*Request*
    
    {
        "email": "sahan@sahanz.com",
        "profile_image": "http://example.com/image.png"
    }
    

*Response*
    
    {
        "name": "sahanlak",
        "login": "sahanlak",
        "email": "sahan@sahanz.com",
        "password": "sahan123",
        "mobile": "772754541",
        "profile_image": "http://example.com/image.png"
    }
    

## Facebook Registration/Sign In
### POST `/auth/facebook`

*Request*
    
    {
        "token" : "facebook_user_access_token"
    }

*Response*

    {
        "token" : "api_user_token"
    }

Below user will be created using the token

*User*
  
    {
        "id": 1,
        "name": "Sahan Hendahewa",
        "login": "10206537203942426",
        "email": "10206537203942426@facebook.com",
        "mobile": "",
        "profile_image": "https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xla1/v/t1.0-1/p50x50/12043004_10206376064074030_1554737818505478355_n.jpg?oh=dbc3eaa6315935acfa4ce7eb90c03173&oe=568A98E8&__gda__=1456352879_161cd7da9dc13888590b5b50762ab5a1",
        "is_activated": true,
        "created_at": "2015-10-26 17:53:54",
        "updated_at": "2015-10-26 17:53:54"
    }

## Google Registration/Sign In
### POST `/auth/google`

*Request*
    
    {
        "token" : "google_user_access_token"
    }

*Response*

    {
        "token" : "api_user_token"
    }

## Password Reset Code
### POST `/auth/mobile_password_reset_code`

First step in password reset process.

*Request*

        {
            "mobile" : "0776787876"
        }

*Response*

        {
            "success" : true
        }

## Do Password Reset
### POST `/auth/mobile_password_reset`

First step in password reset process.

*Request*

        {
            "mobile" : "0776787876",
            "code" : "2334",
            "password" : "sahan123"
        }

*Response*

        {
            "token" : "daskdlasd"
        }

## Get Match
### GET `/matches/{match_id}`

*Response*

    {
        "id": 1,
        "stadium_id": 1,
        "name": "Rugby",
        "scheduled": "2015-10-26 23:40:25",
        "status": 1,
        "created_at": "-0001-11-30 00:00:00",
        "updated_at": "-0001-11-30 00:00:00",
        "stadium": {
            "id": 1,
            "name": "CR FC",
            "created_at": "-0001-11-30 00:00:00",
            "updated_at": "-0001-11-30 00:00:00"
        },
        "teams": [
            {
                "id": 1,
                "match_id": 1,
                "name": "Team #1",
                "image": "",
                "score": 0,
                "created_at": "-0001-11-30 00:00:00",
                "updated_at": "-0001-11-30 00:00:00"
            },
            {
                "id": 2,
                "match_id": 1,
                "name": "Team #2",
                "image": "",
                "score": 10,
                "created_at": "-0001-11-30 00:00:00",
                "updated_at": "-0001-11-30 00:00:00"
            }
        ]
    }

## Get Match Acitvations
### GET `/matches/{id}/activations/{type}`

*Response*

    {
        "enabled" : true
    }

## List Matches
### GET `/matches`

- from : get all matches from a date (required)
- status : query by status

*Response*

    [
        {
            "id": 1,
            "stadium_id": 1,
            "name": "Rugby",
            "scheduled": "2015-10-26 23:40:25",
            "status": 1,
            "created_at": "-0001-11-30 00:00:00",
            "updated_at": "-0001-11-30 00:00:00",
            "stadium": {
                "id": 1,
                "name": "CR FC",
                "created_at": "-0001-11-30 00:00:00",
                "updated_at": "-0001-11-30 00:00:00"
            },
            "teams": [
                {
                    "id": 1,
                    "match_id": 1,
                    "name": "Team #1",
                    "image": "",
                    "score": 0,
                    "created_at": "-0001-11-30 00:00:00",
                    "updated_at": "-0001-11-30 00:00:00"
                },
                {
                    "id": 2,
                    "match_id": 1,
                    "name": "Team #2",
                    "image": "",
                    "score": 10,
                    "created_at": "-0001-11-30 00:00:00",
                    "updated_at": "-0001-11-30 00:00:00"
                }
            ]
        }
    ]

## Live Match Scores
### GET `/matches/live_scores`

*Response*

        [
           {
              "match_id":2,
              "status":1,
              "sub_status":"",
              "teams":[
                 {
                    "team_id":1,
                    "score":0
                 },
                 {
                    "team_id":2,
                    "score":10
                 }
              ]
           }
        ]

## Submit Shoutout
### POST `/matches/{id}/shoutouts`

*Request*

    {
        "message" : "blah blah"
    }

*Response*

    {
        "success" : true
    }

## Submit Selfie
### POST `/matches/{id}/selfies`

*Request*

    {
        "image" : "<url_to_image>"
    }

*Response*

    {
        "success" : true
    }