Feature: As a library user i need to be able to create logger and log information.

  Scenario: Logging warning to rotating file logger
    Given I built a logger with rotating file handler
    When I log warning "test warning"
    Then I should log into file "test warning"

  Scenario: Logging warning to syslog udp logger
    Given I built a logger with syslog handler
    When I log warning "test warning"
    Then I should log into syslog udp "test warning"

  Scenario: Logging with multi handler logger
    Given I built a logger with syslog handler and rotating file handler
    When I log warning "test warning"
    Then I should log into syslog udp "test warning"
    And I should log into file "test warning"

  Scenario: Logging warning to rotating file logger
    Given I built a logger error handler
    When I log an error "test error"
    When I log warning "test warning"
    Then I should have only "1" log in the file
    And I should log into file "test error"

  Scenario: Logging warning to rotating file logger with memory processor
    Given I built a logger with rotating file handler
    Given I add a memory processor
    When I log warning "test warning"
    Then I should have log with memory informaton
    And I should log into file "test warning"

  Scenario: Logging exception to rotating file logger
    Given I built a logger with syslog handler and rotating file handler
    When I log warning "test warning" with an exception
    Then I should log an exception into file log