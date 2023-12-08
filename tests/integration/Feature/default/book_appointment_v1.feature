Feature: Create booking

  Background:
    Given there are working days:
      | id                                   | stafferId                            | date       |
      | 7fd1ad2c-05b1-4814-af37-d74d81a69f1d | e35b98e1-3cec-494c-8b88-e715201e8b75 | 2023-01-01 |
    And the working day "7fd1ad2c-05b1-4814-af37-d74d81a69f1d" has working hours:
      | from  | to    |
      | 09:00 | 12:00 |
      | 13:00 | 17:00 |
    And the working days have been saved
    And the working day "7fd1ad2c-05b1-4814-af37-d74d81a69f1d" has bookings:
      | serviceType  | time  | bookerFirstName | bookerLastName | bookerEmail          |
      | combo        | 10:30 | Jane            | Dawson         | jane.dawson@test.com |

  Scenario: Happy path
    When I send a POST request to "/v1/working-days/7fd1ad2c-05b1-4814-af37-d74d81a69f1d/bookings" with body:
    """
      {
          "time": "09:00",
          "service": "men_haircut",
          "bookerFirstName": "John",
          "bookerLastName": "Doe",
          "bookerEmail": "john.doe@test.com"
      }
    """
    Then the response code is 204

  Scenario: Validation - cannot be booked
    When I send a POST request to "/v1/working-days/7fd1ad2c-05b1-4814-af37-d74d81a69f1d/bookings" with body:
    """
      {
          "time": "10:00",
          "service": "men_haircut",
          "bookerFirstName": "John",
          "bookerLastName": "Doe",
          "bookerEmail": "john.doe@test.com"
      }
    """
    Then the response code is 400
    And the response content is:
    """
      {
          "errors": {
              "general": [
                  "VALIDATION.CANNOT_BE_BOOKED"
              ]
          }
      }
    """

  Scenario: Validation - schema
    When I send a POST request to "/v1/working-days/123/bookings" with body:
    """
      {
          "time": "123",
          "service": "invalid",
          "bookerFirstName": 1,
          "bookerLastName": null,
          "bookerEmail": "1"
      }
    """
    Then the response code is 400
    And the response content is:
    """
      {
          "errors": {
              "id": [
                  "VALIDATION.INVALID_FORMAT"
              ],
              "time": [
                  "VALIDATION.INVALID_FORMAT"
              ],
              "service": [
                  "VALIDATION.NOT_FOUND"
              ],
              "bookerFirstName": [
                  "VALIDATION.INVALID_FORMAT",
                  "VALIDATION.TOO_SHORT"
              ],
              "bookerLastName": [
                  "VALIDATION.MISSING"
              ],
              "bookerEmail": [
                  "VALIDATION.INVALID_FORMAT",
                  "VALIDATION.TOO_SHORT"
              ]
          }
      }
    """
