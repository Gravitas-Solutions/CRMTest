describe('Vehicles page tests', () => {
    const email = "info@protechas.com";
    const password = "InN0V81V06";

    beforeEach(() => {
        cy.login(email, password);
    })
    afterEach(() => {
        cy.visit('/logout');
    })

    it('checks vehicle page elements and basic functions', () => {

        cy.get('[class$=text-right]').then(($dd) => {
            const vehicles = $dd.eq(1).text();

            cy.visit('/vehicles');
            cy.table_exists();

            cy.get('div[role=status]').within(() => {
                cy.contains(vehicles)
                    .should('exist');
            })

            cy.test_table_length();
            cy.test_page_nav();

            cy.get('button').contains('Vehicles').click();
            cy.get('form[id$=vehicle]').should('be.visible');
            cy.get('button').contains('Save').click();
            cy.get('div[class$=danger]').should('be.visible');
            cy.add_dummy_vehicle();
        })
    })
})