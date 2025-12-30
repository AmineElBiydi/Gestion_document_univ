import { Link, useLocation } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { GraduationCap, Menu, X } from "lucide-react";
import { useState } from "react";
import { cn } from "@/lib/utils";

export function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const location = useLocation();
  const isAdmin = location.pathname.startsWith("/admin");

  return (
    <header className="sticky top-0 z-50 w-full border-b border-border/50 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="container flex h-16 items-center justify-between">
        <Link to="/" className="flex items-center gap-3">
          <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-primary-foreground">
            <GraduationCap className="h-6 w-6" />
          </div>
          <div className="flex flex-col">
            <span className="text-lg font-bold text-foreground">UniServices</span>
            <span className="text-xs text-muted-foreground">Portail Administratif</span>
          </div>
        </Link>

        {/* Desktop Navigation */}
        <nav className="hidden md:flex items-center gap-1">
          {!isAdmin ? (
            <>
              <Link to="/demande">
                <Button variant="ghost" className={cn(location.pathname === "/demande" && "bg-accent")}>
                  Nouvelle demande
                </Button>
              </Link>
              <Link to="/suivi">
                <Button variant="ghost" className={cn(location.pathname === "/suivi" && "bg-accent")}>
                  Suivi
                </Button>
              </Link>
            </>
          ) : (
            <>
              <Link to="/admin">
                <Button variant="ghost" className={cn(location.pathname === "/admin" && "bg-accent")}>
                  Dashboard
                </Button>
              </Link>
              <Link to="/admin/demandes">
                <Button variant="ghost" className={cn(location.pathname === "/admin/demandes" && "bg-accent")}>
                  Demandes
                </Button>
              </Link>
              <Link to="/admin/historique">
                <Button variant="ghost" className={cn(location.pathname === "/admin/historique" && "bg-accent")}>
                  Historique
                </Button>
              </Link>
              <Link to="/admin/reclamations">
                <Button variant="ghost" className={cn(location.pathname === "/admin/reclamations" && "bg-accent")}>
                  Réclamations
                </Button>
              </Link>
              <Link to="/admin/claims-dashboard">
                <Button variant="ghost" className={cn(location.pathname === "/admin/claims-dashboard" && "bg-accent")}>
                  Stats
                </Button>
              </Link>
            </>
          )}
        </nav>

        {/* Mobile Menu Button */}
        <Button
          variant="ghost"
          size="icon"
          className="md:hidden"
          onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
        >
          {mobileMenuOpen ? <X /> : <Menu />}
        </Button>
      </div>

      {/* Mobile Menu */}
      {mobileMenuOpen && (
        <div className="md:hidden border-t border-border bg-background animate-slide-up">
          <nav className="container flex flex-col py-4 gap-2">
            {!isAdmin ? (
              <>
                <Link to="/demande" onClick={() => setMobileMenuOpen(false)}>
                  <Button variant="ghost" className="w-full justify-start">
                    Nouvelle demande
                  </Button>
                </Link>
                <Link to="/suivi" onClick={() => setMobileMenuOpen(false)}>
                  <Button variant="ghost" className="w-full justify-start">
                    Suivi
                  </Button>
                </Link>
              </>
            ) : (
              <>
                <Link to="/admin" onClick={() => setMobileMenuOpen(false)}>
                  <Button variant="ghost" className="w-full justify-start">
                    Dashboard
                  </Button>
                </Link>
                <Link to="/admin/demandes" onClick={() => setMobileMenuOpen(false)}>
                  <Button variant="ghost" className="w-full justify-start">
                    Demandes
                  </Button>
                </Link>
                <Link to="/admin/historique" onClick={() => setMobileMenuOpen(false)}>
                  <Button variant="ghost" className="w-full justify-start">
                    Historique
                  </Button>
                </Link>
                <Link to="/admin/reclamations" onClick={() => setMobileMenuOpen(false)}>
                  <Button variant="ghost" className="w-full justify-start">
                    Réclamations
                  </Button>
                </Link>
                <div className="my-2 h-px bg-border" />
                <Link to="/" onClick={() => setMobileMenuOpen(false)}>
                  <Button variant="outline" className="w-full">
                    Espace Étudiant
                  </Button>
                </Link>
              </>
            )}
          </nav>
        </div>
      )}
    </header>
  );
}
