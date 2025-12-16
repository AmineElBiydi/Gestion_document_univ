import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";
import { Lock, Mail, Shield } from "lucide-react";
import { apiEndpoints } from "@/lib/api";

export default function AdminLogin() {
  const navigate = useNavigate();
  const [identifiant, setIdentifiant] = useState("");
  const [password, setPassword] = useState("");
  const [isLoading, setIsLoading] = useState(false);

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      const response = await apiEndpoints.adminLogin({ identifiant, password });
      
      if (response.data.success) {
        localStorage.setItem("admin_token", response.data.data.token);
        localStorage.setItem("adminLoggedIn", "true");
        toast.success("Connexion réussie");
        navigate("/admin");
      } else {
        toast.error(response.data.message || "Erreur de connexion");
      }
    } catch (error: any) {
      toast.error(error.response?.data?.message || "Erreur de connexion");
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-primary/10 via-background to-accent/20 flex items-center justify-center p-4">
      <div className="w-full max-w-md">
        {/* Logo/Header */}
        <div className="text-center mb-8">
          <div className="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-primary text-primary-foreground mb-4">
            <Shield className="h-8 w-8" />
          </div>
          <h1 className="text-2xl font-bold text-foreground">Espace Administration</h1>
          <p className="text-muted-foreground mt-2">
            Connectez-vous pour accéder au panneau d'administration
          </p>
        </div>

        {/* Login Form */}
        <div className="bg-card border border-border rounded-2xl p-6 lg:p-8 shadow-lg">
          <form onSubmit={handleLogin} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="identifiant">Identifiant</Label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input
                  id="identifiant"
                  type="text"
                  value={identifiant}
                  onChange={(e) => setIdentifiant(e.target.value)}
                  placeholder="admin"
                  className="pl-10"
                  required
                />
              </div>
            </div>

            <div className="space-y-2">
              <Label htmlFor="password">Mot de passe</Label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input
                  id="password"
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••••"
                  className="pl-10"
                  required
                />
              </div>
            </div>

            <Button type="submit" className="w-full" disabled={isLoading}>
              {isLoading ? "Connexion en cours..." : "Se connecter"}
            </Button>
          </form>

          {/* Demo credentials hint */}
          <div className="mt-6 p-4 bg-muted/50 rounded-lg">
            <p className="text-xs text-muted-foreground text-center">
              <strong>Démo:</strong> admin / password123
            </p>
          </div>
        </div>

        {/* Back to home */}
        <div className="text-center mt-6">
          <Button variant="ghost" onClick={() => navigate("/")}>
            ← Retour à l'accueil
          </Button>
        </div>
      </div>
    </div>
  );
}
