Feature:
  In order to prove that the likes service works

  Scenario: Posting to like endpoint a blank user_id
    Given the "Content-Type" request header is "application/json"
    Given the request body is:
    """
    {
    "user_id":
    }
    """
    When I request "like" using HTTP POST
    Then the response code is 400

  Scenario: Posting to like endpoint a negative user_id
    Given the "Content-Type" request header is "application/json"
    Given the request body is:
    """
    {
    "user_id":-1
    }
    """
    When I request "like" using HTTP POST
    Then the response code is 400

  Scenario: Posting to like endpoint a correct request
    Given the "Content-Type" request header is "application/json"
    Given the request body is:
    """
    {
    "user_id":1
    }
    """
    When I request "like" using HTTP POST
    Then the response code is 200
    Then the response body contains JSON:
    """
    {
      "user_id": 1,
      "total_likes": "@variableType(integer)",
      "is_email_sent": true
    }
    """
