Feature: Add client controller tests

  Scenario: User adds a client successfully
    When I open "POST" page "/api/v1/clients" with form data
    """
    {
      "firstname": "John",
      "lastname": "Smith"
    }
    """
    Then the response with code "201" should be received

  Scenario: User add a client with one file successfully
    When I open "POST" page "/api/v1/clients" with form data with files
    """
    {
      "firstname": "John",
      "lastname": "Smith",
      "files": [
        {
          "path": "/File/teddy-bear.jpg"
        }
      ]
    }
    """
    Then the response with code "201" should be received

  Scenario: User tries to add a client with too large file and he gets not acceptable code in response with errors
    When I open "POST" page "/api/v1/clients" with form data with files
    """
    {
      "firstname": "John",
      "lastname": "Smith",
      "files": [
        {
          "path": "/File/large-image.jpg"
        }
      ]
    }
    """
    Then the response with code "406" should be received
    And the response should looks like
    """
    {
      "files": [
        {
          "size": "File is too large, allowed size: `2097152`, current size: `2623681`"
        }
      ]
    }
    """

  Scenario: User tries to add a client with non image file and he gets not acceptable code in response with errors
    When I open "POST" page "/api/v1/clients" with form data with files
    """
    {
      "firstname": "John",
      "lastname": "Smith",
      "files": [
        {
          "path": "/File/logs.txt"
        }
      ]
    }
    """
    Then the response with code "406" should be received
    And the response should looks like
    """
    {
      "files": [
        {
          "mimeType": "File is not image"
        }
      ]
    }
    """

  Scenario: User tries to add a client with 2 images and he gets not acceptable code in response with error
    When I open "POST" page "/api/v1/clients" with form data with files
    """
    {
      "firstname": "John",
      "lastname": "Smith",
      "files": [
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        }
      ]
    }
    """
    Then the response with code "406" should be received
    And the response should looks like
    """
    {
      "generalError": "Client cannot have more than 1 files"
    }
    """
    
  Scenario: Users tries to add a client with empty response content and he gets bad request code in response with errors
    When I open "POST" page "/api/v1/clients"
    Then the response with code "400" should be received
    And the response should looks like
    """
    {
      "firstname": "Missing `firstname` parameter",
      "lastname": "Missing `lastname` parameter"
    }
    """

  Scenario: User tries to add a client with invalid data and he gets bad request code in response with errors
    When I open "POST" page "/api/v1/clients" with form data with files
    """
    {
      "firstname": "",
      "lastname": "Smith Smith Smith Smith Smith Smith Smith Smith Smith Smith Smith Smith Smith Smith Smith",
      "files": [
        {
          "path": "/File/teddy-bear.jpg"
        },
        {
          "path": "/File/teddy-bear.jpg"
        }
      ]
    }
    """
    Then the response with code "406" should be received
    And the response should looks like
    """
    {
      "firstname": "Firstname cannot be empty",
      "lastname": "Lastname cannot be longer than 80 characters"
    }
    """