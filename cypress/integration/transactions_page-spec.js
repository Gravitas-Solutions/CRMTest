describe('Transactions page tests', () => {
    const email = "info@protechas.com";
    const password = "InN0V81V06";

    beforeEach(() => {
        cy.login(email, password);
    })
    afterEach(() => {
        cy.visit('/logout');
    })

    // it('checks transaction page elements and basic functions', () => {

    //     cy.get('[class$=text-right]').then(($dd) => {
    //         const transactions = $dd.eq(2).text();
    //         var amount = $dd.eq(0).text();
    //         amount = amount.replace('-', '').replace(',', '');

    //         cy.visit('/transactions');
    //         cy.table_exists();

    //         cy.get('tfoot').within(() => {
    //             cy.contains(amount)
    //                 .should('exist');
    //         })

    //         cy.get('div[role=status]').within(() => {
    //             cy.contains(transactions)
    //                 .should('exist');
    //         })

    //         cy.get('tbody').within(() => {
    //             cy.get('tr[role=row]').eq(0).within(() => {
    //                 cy.get('td').then(($td) => {
    //                     const sample = $td.text();
    //                     cy.log(sample);
    //                 })
    //             })
    //         })
        

    //         cy.test_table_length();
    //         cy.test_page_nav();
    //     })   
    // })

    it('Checks table exists', () => {
        cy.visit('/transactions');
        cy.table_exists();
    })

    it('Checks total amount in dashboard reflects here', () => {

        cy.get('[class$=text-right]').then(($dd) => {
            var amount = $dd.eq(0).text();
            amount = amount.replace('-', '').replace(',', '');

            cy.visit('/transactions');

            cy.get('tfoot').within(() => {
                cy.contains(amount)
                    .should('exist');
            })
        })
    })

    it('Checks total transactions in dashboard reflects here', () => {

        cy.get('[class$=text-right]').then(($dd) => {
            const transactions = $dd.eq(2).text();

            cy.visit('/transactions');

            cy.get('div[role=status]').within(() => {
                cy.contains(transactions)
                    .should('exist');
            })
        })
    })

    it('Test table length function', () => {
        cy.visit('/transactions');
        cy.test_table_length();
    })

    it('Test table navigation function', () => {
        cy.visit('/transactions');
        cy.test_page_nav();
    })
})