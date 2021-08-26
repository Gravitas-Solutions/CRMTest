describe('Invoices page tests', () => {
    const email = "thawbaker@comeseeclay.com";
    const password = "InN0V81V09";

    beforeEach(() => {
        cy.login(email, password);
    })
    afterEach(() => {
        cy.visit('/logout');
    })

    it('Checks table exists', () => {
        cy.visit('/invoices');
        cy.table_exists();
    })

    it('Test table length function', () => {
        cy.visit('/invoices');
        cy.test_table_length();
    })

    it('Test table navigation function', () => {
        cy.visit('/invoices');
        cy.test_page_nav();
    })
})