describe('Logging into crm', () => {
    it('Attempts login with no credentials', () => {
        cy.visit('/login');

        cy.contains('Login').click();

        cy.get('div[class$=danger]').within(() => {
            cy.get('p').should('contain.text', 'The Email/Username field is required');
            cy.get('p').should('contain.text', 'The Password field is required');
        })
    })
    
    it('Attempts correct login', () => {
        cy.visit('/login');

        const email = "info@protechas.com";
        const password = "InN0V81V06";

        cy.get('input[name=identity]')
            .type(email)
            .should('have.value', email);

        cy.get('input[name=password]').type(password);

        cy.contains('Login').click();

        cy.url().should('include', '/home');

        cy.visit('/logout');
  })
})