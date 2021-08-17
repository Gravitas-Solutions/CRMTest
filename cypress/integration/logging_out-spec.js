describe('Logging out of crm', () => {
    const email = "info@protechas.com";
    const password = "InN0V81V06";

    beforeEach(() => {
        cy.login(email, password);
    })

    it('Attempts logout using ui', () => {
        cy.get('div[class^=avatar-sm]').click();

        cy.contains('Logout').click();

        cy.url().should('include', '/login');
    })
})