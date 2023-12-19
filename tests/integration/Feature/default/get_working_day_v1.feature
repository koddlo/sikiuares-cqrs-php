Feature: Get working day

  Background:
    Given there are working days:
      | id                                   | stafferId                            | date       |
      | 7fd1ad2c-05b1-4814-af37-d74d81a69f1d | e35b98e1-3cec-494c-8b88-e715201e8b75 | 2023-01-01 |
      | 2bd1ad2c-05b1-4814-af37-d74d81a69fc8 | e35b98e1-3cec-494c-8b88-e715201e8b75 | 2023-01-02 |
    And the working day "7fd1ad2c-05b1-4814-af37-d74d81a69f1d" has working hours:
      | from  | to    |
      | 09:00 | 12:00 |
      | 13:00 | 17:00 |
    And the working day "2bd1ad2c-05b1-4814-af37-d74d81a69fc8" has working hours:
      | from  | to    |
      | 08:00 | 18:00 |
    And the working days have been saved
    And the working day "7fd1ad2c-05b1-4814-af37-d74d81a69f1d" has bookings:
      | serviceType | time  | bookerFirstName | bookerLastName | bookerEmail          |
      | men_haircut | 09:00 | John            | Doe            | john.doe@test.com    |
      | combo       | 10:30 | Jane            | Dawson         | jane.dawson@test.com |

  Scenario: Happy path
    When I send a GET request to "/v1/working-days/7fd1ad2c-05b1-4814-af37-d74d81a69f1d"
    Then the response code is 200
    And the response content is:
    """
      {
          "id": "7fd1ad2c-05b1-4814-af37-d74d81a69f1d",
          "stafferId": "e35b98e1-3cec-494c-8b88-e715201e8b75",
          "date": "2023-01-01",
          "workingHours": [
              {
                  "from": "09:00",
                  "to": "12:00"
              },
              {
                  "from": "13:00",
                  "to": "17:00"
              }
          ],
          "bookings": [
              {
                  "from": "09:00",
                  "to": "10:00",
                  "bookerFirstName": "John",
                  "bookerLastName": "Doe",
                  "bookerEmail": "john.doe@test.com"
              },
              {
                  "from": "10:30",
                  "to": "12:00",
                  "bookerFirstName": "Jane",
                  "bookerLastName": "Dawson",
                  "bookerEmail": "jane.dawson@test.com"
              }
          ]
      }
    """

  Scenario: Alternative path - no bookings
    When I send a GET request to "/v1/working-days/2bd1ad2c-05b1-4814-af37-d74d81a69fc8"
    Then the response code is 200
    And the response content is:
    """
      {
          "id": "2bd1ad2c-05b1-4814-af37-d74d81a69fc8",
          "stafferId": "e35b98e1-3cec-494c-8b88-e715201e8b75",
          "date": "2023-01-02",
          "workingHours": [
              {
                  "from": "08:00",
                  "to": "18:00"
              }
          ],
          "bookings": []
      }
    """


  Scenario: 404 - not found
    When I send a GET request to "/v1/working-days/123"
    Then the response code is 404