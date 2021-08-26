describe('Invoices page tests', () => {
    const email = "info@protechas.com";
    const password = "InN0V81V06";

    beforeEach(() => {
        cy.login(email, password);
    })
    afterEach(() => {
        cy.visit('/logout');
    })

    it('Navigates to Vehicles page', () => {

        cy.get('a[class$=nav-link]')
            .contains('Vehicles')
            .click()

        cy.url().should('include', '/vehicles');
    })
})