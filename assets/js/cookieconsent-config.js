/**
 * All config. options available here:
 * https://cookieconsent.orestbida.com/reference/configuration-reference.html
 */
CookieConsent.run({
    guiOptions: {
        consentModal: {
            layout: 'bar',                      // box,cloud,bar
            position: 'bottom',           // bottom,middle,top + left,right,center
            equalWeightButtons: true,
            flipButtons: false
        },
        preferencesModal: {
            layout: 'box',                      // box,bar
            equalWeightButtons: true,
            flipButtons: false
        }
    },

    categories: {
        necessary: {
            enabled: true,
            readOnly: true
        },
        analytics: {},
        ads: {}
    },

    language: {
        default: 'de',
        translations: {
            de: {
                consentModal: {
                    title: 'Wir benötigen Ihre Zustimmung.',
                    description: 'Um unsere Webseiten für Sie bestmöglich zu gestalten, zu verbessern und interessengerechte Inhalten bereitzustellen, verwenden wir Cookies. Durch Bestätigen des Buttons „Alle akzeptieren“ stimmen Sie der Verwendung zu. Über den Button „Details anzeigen“ können Sie auswählen, welche Cookies Sie zulassen wollen. Weitere Informationen erhalten Sie in unserer Datenschutzerklärung.',
                    acceptAllBtn: 'Alle akzeptieren',
                    acceptNecessaryBtn: 'Auswahl akzeptieren',
                    showPreferencesBtn: 'Details anzeigen'
                },
                preferencesModal: {
                    title: '',
                    acceptAllBtn: 'Alle akzeptieren',
                    acceptNecessaryBtn: 'Auswahl akzeptieren',
                    savePreferencesBtn: 'Cookies ablehnen',
                    closeIconLabel: 'Modal schließen',
                    sections: [
                        {
                            title: 'Wir benötigen Ihre Zustimmung.',
                            description: 'Um unsere Webseiten für Sie bestmöglich zu gestalten, zu verbessern und interessengerechte Inhalten bereitzustellen, verwenden wir Cookies. Durch Bestätigen des Buttons „Alle akzeptieren“ stimmen Sie der Verwendung zu. Über den Button „Details anzeigen“ können Sie auswählen, welche Cookies Sie zulassen wollen. Weitere Informationen erhalten Sie in unserer Datenschutzerklärung'
                        },
                       
                        {
                            title: 'Funktional <span>Mehr Informationen</span>',
                            description: 'Die technische Speicherung oder der Zugang ist unbedingt erforderlich für den rechtmäßigen Zweck, die Nutzung eines bestimmten Dienstes zu ermöglichen, der vom Teilnehmer oder Nutzer ausdrücklich gewünscht wird, oder für den alleinigen Zweck, die Übertragung einer Nachricht über ein elektronisches Kommunikationsnetz durchzuführen.',

                            linkedCategory: 'necessary'
                        },
                        {
                            title: 'Statistisch <span>Mehr Informationen</span>',
                            description: 'Die technische Speicherung oder der Zugriff, der ausschließlich zu anonymen statistischen Zwecken verwendet wird. Ohne eine Vorladung, die freiwillige Zustimmung deines Internetdienstanbieters oder zusätzliche Aufzeichnungen von Dritten können die zu diesem Zweck gespeicherten oder abgerufenen Informationen allein in der Regel nicht dazu verwendet werden, dich zu identifizieren.',
                            linkedCategory: 'analytics'
                        },
                        {
                            title: 'Marketing <span>Mehr Informationen</span>',
                            description: 'Die technische Speicherung oder der Zugriff ist erforderlich, um Nutzerprofile zu erstellen, um Werbung zu versenden oder um den Nutzer auf einer Website oder über mehrere Websites hinweg zu ähnlichen Marketingzwecken zu verfolgen.',
                            linkedCategory: 'ads',
                        }
                    ]
                }
            }
        }
    }
});