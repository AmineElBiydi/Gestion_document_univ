import { GraduationCap, Mail, Phone, MapPin } from "lucide-react";

export function Footer() {
  return (
    <footer className="border-t border-border bg-muted/30">
      <div className="container py-12">
        <div className="grid gap-8 md:grid-cols-4">
          <div className="space-y-4">
            <div className="flex items-center gap-3">
              <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-primary-foreground">
                <GraduationCap className="h-6 w-6" />
              </div>
              <span className="text-lg font-bold">UniServices</span>
            </div>
            <p className="text-sm text-muted-foreground">
              Plateforme de gestion des services administratifs universitaires. 
              Simplifiez vos démarches en quelques clics.
            </p>
          </div>

          <div className="space-y-4">
            <h4 className="font-semibold text-foreground">Services</h4>
            <ul className="space-y-2 text-sm text-muted-foreground">
              <li>Attestation de scolarité</li>
              <li>Attestation de réussite</li>
              <li>Relevé de notes</li>
              <li>Convention de stage</li>
            </ul>
          </div>

          <div className="space-y-4">
            <h4 className="font-semibold text-foreground">Liens utiles</h4>
            <ul className="space-y-2 text-sm text-muted-foreground">
              <li>Guide d'utilisation</li>
              <li>FAQ</li>
              <li>Politique de confidentialité</li>
              <li>Mentions légales</li>
            </ul>
          </div>

          <div className="space-y-4">
            <h4 className="font-semibold text-foreground">Contact</h4>
            <ul className="space-y-3 text-sm text-muted-foreground">
              <li className="flex items-center gap-2">
                <Mail className="h-4 w-4 text-primary" />
                contact@uniservices.ma
              </li>
              <li className="flex items-center gap-2">
                <Phone className="h-4 w-4 text-primary" />
                +212 5XX-XXXXXX
              </li>
              <li className="flex items-start gap-2">
                <MapPin className="h-4 w-4 text-primary mt-0.5" />
                <span>Campus Universitaire, Ville, Maroc</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-8 pt-8 border-t border-border text-center text-sm text-muted-foreground">
          <p>© {new Date().getFullYear()} UniServices. Tous droits réservés.</p>
        </div>
      </div>
    </footer>
  );
}
