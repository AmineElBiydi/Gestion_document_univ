import React from 'react';
import { Document, Page, Text, View, StyleSheet, Image, Font } from '@react-pdf/renderer';

interface InternshipData {
    studentName: string;
    filiere: string;
    dateDebut: string;
    dateFin: string;
    entrepriseNom: string;
    entrepriseAdresse: string;
    entrepriseTel: string;
    entrepriseEmail: string;
    entrepriseRepresentant: string;
    entrepriseQualite: string;
    encadrantNom: string;
    tuteurNom: string;
    themeStage: string;
    dateSignature: string;
}

interface Signatures {
    student: string | null;
    coordinator: string | null;
    establishment: string | null;
    enterprise: string | null;
}

interface ConventionPDFProps {
    data: InternshipData;
    signatures: Signatures;
}

// Create styles
const styles = StyleSheet.create({
    page: {
        padding: 40,
        fontSize: 10,
        fontFamily: 'Helvetica',
    },
    header: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        marginBottom: 20,
        paddingBottom: 10,
        borderBottom: 2,
        borderBottomColor: '#000',
    },
    headerLeft: {
        fontSize: 9,
        fontWeight: 'bold',
    },
    headerRight: {
        fontSize: 9,
        fontWeight: 'bold',
        textAlign: 'right',
    },
    logoPlaceholder: {
        width: 50,
        height: 50,
        backgroundColor: '#e5e7eb',
        borderRadius: 25,
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
    },
    title: {
        fontSize: 16,
        fontWeight: 'bold',
        textAlign: 'center',
        marginBottom: 5,
    },
    subtitle: {
        fontSize: 9,
        fontStyle: 'italic',
        textAlign: 'center',
        marginBottom: 15,
    },
    sectionTitle: {
        fontSize: 11,
        fontWeight: 'bold',
        textAlign: 'center',
        marginBottom: 10,
    },
    paragraph: {
        marginBottom: 10,
        lineHeight: 1.5,
        textAlign: 'justify',
    },
    bold: {
        fontWeight: 'bold',
    },
    article: {
        marginBottom: 10,
    },
    articleTitle: {
        fontSize: 10,
        fontWeight: 'bold',
        marginBottom: 5,
    },
    articleContent: {
        fontSize: 9,
        lineHeight: 1.5,
        textAlign: 'justify',
    },
    signatureSection: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        marginTop: 30,
        marginBottom: 20,
    },
    signatureBox: {
        width: '45%',
        alignItems: 'center',
    },
    signatureLabel: {
        fontSize: 9,
        marginBottom: 10,
        textAlign: 'center',
    },
    signatureImage: {
        width: 120,
        height: 60,
        marginBottom: 5,
        border: 1,
        borderColor: '#d1d5db',
    },
    signatureName: {
        fontSize: 9,
        fontWeight: 'bold',
        marginTop: 5,
    },
    pageNumber: {
        position: 'absolute',
        fontSize: 9,
        bottom: 20,
        left: 0,
        right: 0,
        textAlign: 'center',
        color: 'grey',
    },
    link: {
        color: '#2563eb',
    },
    rightAlign: {
        textAlign: 'right',
    },
});

const ConventionPDF: React.FC<ConventionPDFProps> = ({ data, signatures }) => (
    <Document>
        {/* Page 1 */}
        <Page size="A4" style={styles.page}>
            {/* Header */}
            <View style={styles.header}>
                <View style={styles.headerLeft}>
                    <Text>Université Abdelmalek Essaâdi</Text>
                    <Text>Ecole Nationale des Sciences</Text>
                    <Text>Appliquées</Text>
                    <Text>Tétouan</Text>
                </View>

                <View style={styles.logoPlaceholder}>
                    <Text style={{ fontSize: 8 }}>LOGO</Text>
                </View>

                <View style={styles.headerRight}>
                    <Text>جامعة عبد المالك السعدي</Text>
                    <Text>المدرسة الوطنية للعلوم التطبيقية</Text>
                    <Text>تطوان</Text>
                </View>
            </View>

            <Text style={styles.title}>CONVENTION DE STAGE</Text>
            <Text style={styles.subtitle}>(2 exemplaires imprimés en recto-verso)</Text>

            <Text style={styles.sectionTitle}>ENTRE</Text>

            <View style={styles.paragraph}>
                <Text>L'École Nationale des Sciences Appliquées, Université Abdelmalek Essaâdi - Tétouan</Text>
                <Text>B.P. 2222, Mhannech II, Tétouan, Maroc</Text>
                <Text>Tél. +212 39 68 80 27; Fax. +212 39 99 46 24. Web. <Text style={styles.link}>https://ensa-tetouan.ac.ma</Text></Text>
                <Text>Représenté par le Professeur Kamal REKLAOUI en qualité de Directeur.</Text>
                <Text style={styles.rightAlign}>Ci-après, dénommé <Text style={styles.bold}>l'Etablissement</Text></Text>
            </View>

            <Text style={styles.sectionTitle}>ET</Text>

            <View style={styles.paragraph}>
                <Text>La Société : <Text style={styles.bold}>{data.entrepriseNom}</Text></Text>
                <Text>Adresse : <Text style={styles.bold}>{data.entrepriseAdresse}</Text></Text>
                <Text>Tél : <Text style={styles.bold}>{data.entrepriseTel}</Text> Email: <Text style={styles.bold}>{data.entrepriseEmail}</Text></Text>
                <Text>Représentée par Monsieur ................................................ en qualité ................................................</Text>
                <Text style={styles.rightAlign}>Ci-après dénommée <Text style={styles.bold}>L'ENTREPRISE</Text></Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 1 : Engagement</Text>
                <Text style={styles.articleContent}>
                    <Text style={styles.bold}>L'ENTREPRISE</Text> accepte de recevoir à titre de stagiaire <Text style={styles.bold}>{data.studentName}</Text> étudiant de la filière du Cycle Ingénieur « {data.filiere} » de l'ENSA de Tétouan, Université Abdelmalek Essaâdi (Tétouan), pour une période allant du <Text style={styles.bold}>{data.dateDebut}</Text> au <Text style={styles.bold}>{data.dateFin}</Text>
                </Text>
                <Text style={[styles.articleContent, { marginTop: 5 }]}>
                    En aucun cas, cette convention ne pourra autoriser les étudiants à s'absenter durant la période des contrôles ou des enseignements.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 2 : Objet</Text>
                <Text style={styles.articleContent}>
                    Le stage aura pour objet essentiel d'assurer l'application pratique de l'enseignement donné par <Text style={styles.bold}>l'Etablissement</Text>, et ce, en organisant des visites sur les installations et en réalisant des études proposées par <Text style={styles.bold}>L'ENTREPRISE</Text>.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 3 : Encadrement et suivi</Text>
                <Text style={styles.articleContent}>
                    Pour accompagner le Stagiaire durant son stage, et ainsi instaurer une véritable collaboration L'ENTREPRISE/Stagiaire/Etablissement, <Text style={styles.bold}>L'ENTREPRISE</Text> désigne Mme/Mr <Text style={styles.bold}>{data.encadrantNom}</Text> encadrant(e) et parrain(e), pour superviser et assurer la qualité du travail fourni par le Stagiaire. L'Établissement désigne <Text style={styles.bold}>{data.tuteurNom}</Text> en tant que tuteur qui procurera une assistance pédagogique.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 4 : Programme:</Text>
                <Text style={styles.articleContent}>
                    Le thème du stage est: « <Text style={styles.bold}>{data.themeStage || '...........................................................................................'}</Text> »
                </Text>
                <Text style={[styles.articleContent, { marginTop: 5 }]}>
                    Ce programme a été défini conjointement par l'Etablissement, <Text style={styles.bold}>L'ENTREPRISE</Text> et le Stagiaire. Le contenu de ce programme doit permettre au Stagiaire une réflexion en relation avec les enseignements ou le projet de fin d'études qui s'inscrit dans le programme de formation de l'Établissement.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 5 : Indemnité de stage</Text>
                <Text style={styles.articleContent}>
                    Au cours du stage, l'étudiant ne pourra prétendre à aucun salaire de la part de <Text style={styles.bold}>L'ENTREPRISE</Text>.
                </Text>
            </View>

            <Text style={styles.pageNumber} render={({ pageNumber }) => `${pageNumber}`} fixed />
        </Page>

        {/* Page 2 */}
        <Page size="A4" style={styles.page}>
            <View style={styles.paragraph}>
                <Text style={styles.articleContent}>
                    Cependant, si <Text style={styles.bold}>L'ENTREPRISE</Text> et l'étudiant le conviennent, ce dernier pourra recevoir une indemnité forfaitaire de la part de <Text style={styles.bold}>L'ENTREPRISE</Text> des frais occasionnés par la mission confiée à l'étudiant.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 6 : Règlement</Text>
                <Text style={styles.articleContent}>
                    Pendant la durée du stage, le Stagiaire reste placé sous la responsabilité de <Text style={styles.bold}>l'Etablissement</Text>. Cependant, l'étudiant est tenu d'informer l'école dans un délai de <Text style={styles.bold}>24h sur toute modification</Text> portant sur la convention déjà signée, sinon il en assumera toute sa responsabilité sur son non-respect de la convention signée par l'école.
                </Text>
                <Text style={[styles.articleContent, { marginTop: 5 }]}>
                    Toutefois, le Stagiaire est soumis à la discipline et au règlement intérieur de <Text style={styles.bold}>L'ENTREPRISE</Text>. En cas de manquement, <Text style={styles.bold}>L'ENTREPRISE</Text> se réserve le droit de mettre fin au stage après en avoir convenu avec le Directeur de l'Établissement.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 7 : Confidentialité</Text>
                <Text style={styles.articleContent}>
                    Le Stagiaire et l'ensemble des acteurs liés à son travail (l'administration de <Text style={styles.bold}>l'Etablissement</Text>, le parrain pédagogique ...) sont tenus au secret professionnel. Ils s'engagent à ne pas diffuser les informations recueillies à des fins de publications, conférences, communications, sans raccord préalable de <Text style={styles.bold}>L'ENTREPRISE</Text>. Cette obligation demeure valable après l'expiration du stage.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 8 : Assurance accident de travail</Text>
                <Text style={styles.articleContent}>
                    Le stagiaire devra obligatoirement souscrire une assurance couvrant la Responsabilité Civile et Accident de Travail, durant les stages et trajets effectués. En cas d'accident de travail survenant durant la période du stage, <Text style={styles.bold}>L'ENTREPRISE</Text> s'engage à faire parvenir immédiatement à l'Établissement toutes les informations indispensables à la déclaration dudit accident.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 9: Evaluation de L'ENTREPRISE</Text>
                <Text style={styles.articleContent}>
                    Le stage accompli, le parrain établira un rapport d'appréciations générales sur le travail effectué et le comportement du Stagiaire durant son séjour chez <Text style={styles.bold}>L'ENTREPRISE</Text>. <Text style={styles.bold}>L'ENTREPRISE</Text> remettra au Stagiaire une attestation indiquant la nature et la durée des travaux effectués.
                </Text>
            </View>

            <View style={styles.article}>
                <Text style={styles.articleTitle}>Article 10 : Rapport de stage</Text>
                <Text style={styles.articleContent}>
                    A l'issue de chaque stage, le Stagiaire rédigera un rapport de stage faisant état de ses travaux et de son vécu au sein de <Text style={styles.bold}>L'ENTREPRISE</Text>. Ce rapport sera communiqué à <Text style={styles.bold}>L'ENTREPRISE</Text> et restera strictement confidentiel.
                </Text>
            </View>

            <Text style={[styles.paragraph, { textAlign: 'center', marginTop: 20 }]}>
                Fait à Tétouan en deux exemplaires, le <Text style={styles.bold}>{data.dateSignature}</Text>
            </Text>

            {/* First row of signatures */}
            <View style={styles.signatureSection}>
                <View style={styles.signatureBox}>
                    <Text style={styles.signatureLabel}>Nom et signature du Stagiaire</Text>
                    {signatures.student && (
                        <Image src={signatures.student} style={styles.signatureImage} />
                    )}
                    <Text style={styles.signatureName}>{data.studentName}</Text>
                </View>
                <View style={styles.signatureBox}>
                    <Text style={styles.signatureLabel}>Le Coordonnateur de la filière</Text>
                    {signatures.coordinator && (
                        <Image src={signatures.coordinator} style={styles.signatureImage} />
                    )}
                </View>
            </View>

            {/* Second row of signatures */}
            <View style={styles.signatureSection}>
                <View style={styles.signatureBox}>
                    <Text style={styles.signatureLabel}>Signature et cachet de L'Etablissement</Text>
                    {signatures.establishment && (
                        <Image src={signatures.establishment} style={styles.signatureImage} />
                    )}
                    <Text style={[styles.signatureName, { color: '#2563eb' }]}>SAID RIAN</Text>
                </View>
                <View style={styles.signatureBox}>
                    <Text style={styles.signatureLabel}>Signature et cachet de L'ENTREPRISE</Text>
                    {signatures.enterprise && (
                        <Image src={signatures.enterprise} style={styles.signatureImage} />
                    )}
                </View>
            </View>

            <Text style={styles.pageNumber} render={({ pageNumber }) => `${pageNumber}`} fixed />
        </Page>
    </Document>
);

export default ConventionPDF;
