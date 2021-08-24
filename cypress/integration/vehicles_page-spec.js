describe('Vehicles page tests', () => {
    const email = "info@protechas.com";
    const password = "InN0V81V06";

    beforeEach(() => {
        cy.login(email, password);
    })
    afterEach(() => {
        cy.visit('/logout');
    })

    // it('checks vehicle page elements and basic functions', () => {

    //     cy.get('[class$=text-right]').then(($dd) => {
    //         const vehicles = $dd.eq(1).text();

    //         cy.visit('/vehicles');
    //         cy.table_exists();

    //         cy.get('div[role=status]').within(() => {
    //             cy.contains(vehicles)
    //                 .should('exist');
    //         })

    //         cy.test_table_length();
    //         cy.test_page_nav();

    //         cy.get('button').contains('Vehicles').click();
    //         cy.get('form[id$=vehicle]').should('be.visible');
    //         cy.get('button').contains('Save').click();
    //         cy.get('div[class$=danger]').should('be.visible');
    //         cy.add_dummy_vehicle();
    //     })
    // })

    it('Checks table exists', () => {
        cy.visit('/vehicles');
        cy.table_exists();
    })

    it('Checks total vehicles in dashboard reflects here', () => {

        cy.get('[class$=text-right]').then(($dd) => {
            const vehicles = $dd.eq(1).text();

            cy.visit('/vehicles');

            cy.get('div[role=status]').within(() => {
                cy.contains(vehicles)
                    .should('exist');
            })
        })
    })

    it('Test table length function', () => {
        cy.visit('/vehicles');
        cy.test_table_length();
    })

    it('Test table navigation function', () => {
        cy.visit('/vehicles');
        cy.test_page_nav();
    })

    it('Test adding single vehicle with empty fields', () => {
        cy.visit('/vehicles');
        cy.get('button').contains('Vehicles').click();
        cy.get('form[id$=vehicle]').should('be.visible');
        cy.get('button').contains('Save').click();
        cy.get('div[class$=danger]').should('be.visible');
    })

    it('Test adding single vehicle', () => {
        cy.visit('/vehicles');
        cy.add_dummy_vehicle();
    })
})