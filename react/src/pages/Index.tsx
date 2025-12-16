import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Layout } from "@/components/layout/Layout";
import {
  FileText,
  Clock,
  Shield,
  ArrowRight,
  CheckCircle2,
  GraduationCap,
  FileCheck,
  ScrollText,
  Briefcase,
} from "lucide-react";

const features = [
  {
    icon: FileText,
    title: "Demandes simplifiées",
    description: "Soumettez vos demandes de documents en quelques clics, sans déplacement.",
  },
  {
    icon: Clock,
    title: "Suivi en temps réel",
    description: "Suivez l'état de vos demandes à chaque étape du processus.",
  },
  {
    icon: Shield,
    title: "Sécurisé et fiable",
    description: "Vos données sont protégées et traitées en toute confidentialité.",
  },
];

const documents = [
  {
    icon: GraduationCap,
    title: "Attestation de scolarité",
    description: "Prouvez votre inscription actuelle",
  },
  {
    icon: FileCheck,
    title: "Attestation de réussite",
    description: "Validez votre réussite académique",
  },
  {
    icon: ScrollText,
    title: "Relevé de notes",
    description: "Obtenez vos résultats détaillés",
  },
  {
    icon: Briefcase,
    title: "Convention de stage",
    description: "Formalisez votre stage professionnel",
  },
];

const steps = [
  {
    number: "01",
    title: "Identifiez-vous",
    description: "Entrez votre email, numéro Apogée et CIN pour vous authentifier.",
  },
  {
    number: "02",
    title: "Choisissez votre document",
    description: "Sélectionnez le type de document dont vous avez besoin.",
  },
  {
    number: "03",
    title: "Complétez les informations",
    description: "Remplissez les détails spécifiques à votre demande.",
  },
  {
    number: "04",
    title: "Recevez votre document",
    description: "Téléchargez votre document une fois validé par l'administration.",
  },
];

export default function Index() {
  return (
    <Layout>
      {/* Hero Section */}
      <section className="relative overflow-hidden bg-gradient-to-b from-background to-accent/30 py-20 lg:py-32">
        <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-primary/5 via-transparent to-transparent" />
        <div className="container relative">
          <div className="mx-auto max-w-3xl text-center">
            <div className="mb-6 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-2 text-sm font-medium text-primary animate-fade-in">
              <CheckCircle2 className="h-4 w-4" />
              Portail officiel de l'université
            </div>
            <h1 className="mb-6 text-4xl font-extrabold tracking-tight text-foreground sm:text-5xl lg:text-6xl animate-slide-up">
              Vos démarches administratives{" "}
              <span className="gradient-text">simplifiées</span>
            </h1>
            <p className="mb-10 text-lg text-muted-foreground animate-slide-up" style={{ animationDelay: "0.1s" }}>
              Demandez vos documents universitaires en ligne, suivez vos demandes en temps réel
              et recevez vos attestations sans vous déplacer.
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up" style={{ animationDelay: "0.2s" }}>
              <Link to="/demande">
                <Button variant="hero" size="xl">
                  Faire une demande
                  <ArrowRight className="ml-2 h-5 w-5" />
                </Button>
              </Link>
              <Link to="/suivi">
                <Button variant="outline" size="xl">
                  Suivre ma demande
                </Button>
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Documents Section */}
      <section className="py-20 bg-background">
        <div className="container">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-foreground mb-4">
              Documents disponibles
            </h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Accédez à tous vos documents administratifs depuis une seule plateforme
            </p>
          </div>
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            {documents.map((doc, index) => (
              <div
                key={doc.title}
                className="group relative rounded-2xl border border-border bg-card p-6 shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-primary/30"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-primary-foreground">
                  <doc.icon className="h-7 w-7" />
                </div>
                <h3 className="mb-2 font-semibold text-foreground">{doc.title}</h3>
                <p className="text-sm text-muted-foreground">{doc.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-20 bg-muted/30">
        <div className="container">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-foreground mb-4">
              Pourquoi utiliser UniServices ?
            </h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Une plateforme conçue pour faciliter votre vie universitaire
            </p>
          </div>
          <div className="grid gap-8 md:grid-cols-3">
            {features.map((feature, index) => (
              <div
                key={feature.title}
                className="flex flex-col items-center text-center p-8 rounded-2xl bg-card border border-border shadow-sm"
              >
                <div className="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-secondary/20 text-secondary">
                  <feature.icon className="h-8 w-8" />
                </div>
                <h3 className="mb-3 text-xl font-semibold text-foreground">
                  {feature.title}
                </h3>
                <p className="text-muted-foreground">{feature.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* How it works Section */}
      <section className="py-20 bg-background">
        <div className="container">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-foreground mb-4">
              Comment ça marche ?
            </h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Obtenez vos documents en 4 étapes simples
            </p>
          </div>
          <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            {steps.map((step, index) => (
              <div key={step.number} className="relative">
                <div className="text-6xl font-extrabold text-primary/10 mb-4">
                  {step.number}
                </div>
                <h3 className="text-lg font-semibold text-foreground mb-2">
                  {step.title}
                </h3>
                <p className="text-sm text-muted-foreground">{step.description}</p>
                {index < steps.length - 1 && (
                  <div className="hidden lg:block absolute top-8 right-0 w-1/2 border-t-2 border-dashed border-primary/20" />
                )}
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-primary">
        <div className="container">
          <div className="mx-auto max-w-3xl text-center">
            <h2 className="text-3xl font-bold text-primary-foreground mb-4">
              Prêt à commencer ?
            </h2>
            <p className="text-primary-foreground/80 mb-8">
              Faites votre première demande dès maintenant et recevez votre document rapidement.
            </p>
            <Link to="/demande">
              <Button variant="secondary" size="xl">
                Commencer ma demande
                <ArrowRight className="ml-2 h-5 w-5" />
              </Button>
            </Link>
          </div>
        </div>
      </section>
    </Layout>
  );
}
