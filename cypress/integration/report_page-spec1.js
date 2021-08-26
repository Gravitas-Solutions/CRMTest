describe('Invoices page tests', () => {
    const email = "info@amazon.com";
    const password = "InN0V81V01";

    beforeEach(() => {
        cy.login(email, password);
    })
    afterEach(() => {
        cy.visit('/logout');
    })

    it('Checks table exists', () => {
        cy.visit('/reports');
        cy.table_exists();
    })

    it('Test table length function', () => {
        cy.visit('/reports');
        cy.test_table_length();
    })

    it('Test table navigation function', () => {
        cy.visit('/reports');
        cy.test_page_nav();
    })
})