// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
Cypress.Commands.add('login', (email, password) => {
    cy.visit('/')
    
    cy.get('input[name=identity]').type(email)
    cy.get('input[name=password]').type(password)
    cy.get('form').submit()

    cy.wait(2000)
})

Cypress.Commands.add('test_table_length', () => {
    cy.get('div[class^=dataTables]').within(() => {
        cy.get('select[class^=form-control]')
        .should('have.value', '10')
        
        cy.get('select[class^=form-control]')
        .select('50')
        .should('have.value', '50')
    })
})

Cypress.Commands.add('test_page_nav', () => {
    cy.get('div[role=status]')
        .invoke('text')
        .then((text1) => {
            cy.get('li[id$=next]')
                .click()

            cy.get('div[role=status]')
                .invoke('text')
                .should((text2) => {
                expect(text1).not.to.eq(text2)
                })
            })
})

Cypress.Commands.add('table_exists', () => {
    cy.get('tbody').within(() => {
        cy.get('tr[role=row]')
        .should('exist')
    })
})

Cypress.Commands.add('select_date', (day) => {
    const selected = day.toString()
    cy.get('div[class^=date]').within(() => {
        cy.get('td[class=day]').contains(selected).click()
    })
})

Cypress.Commands.add('add_dummy_vehicle', () => {
    const input_var = "DUMMY"
    cy.get('form[id$=new_vehicle]').within(() => {
        cy.get('input[name$=plate]').type(input_var)
        cy.get('input[name$=model]').type(input_var)
        cy.get('input[name$=color]').type(input_var)
        cy.get('input[name$=make]').type(input_var)
        cy.get('select[name$=axles]').select('Two axles')
        cy.get('select[name$=tagtype]').select('Dealer')
        cy.get('input[name^=start]').click()
        cy.get('select[name$=tagtype]').select('Dealer')
        cy.get('select[name$=location]').select('Texas')
        cy.get('input[name$=year]').type('2021')
        cy.get('select[name^=dept]').select('Unassigned')
        cy.get('input[name^=start]').click()
    })
    cy.select_date(1)
    cy.get('button').contains('Save').click()

    cy.wait(2000);
    cy.on('window:alert', (txt) => {
        expect(txt).to.contains('Vehicle saved');
    })
})

Cypress.Commands.add('edit_dummy_vehicle', () => {
    const input_var = "DUMMY"
    cy.get('input[type=search]').type(input_var)
    cy.get('tbody').within(() => {
        cy.get('tr[role=row]').eq(0).within(() => {
            cy.get('')
        })
    })
})
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
