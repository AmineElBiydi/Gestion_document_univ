import { useState } from "react";
import { Layout } from "@/components/layout/Layout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { StatusBadge } from "@/components/shared/StatusBadge";
import { toast } from "sonner";
import {
  Search,
  FileText,
  Calendar,
  Download,
  MessageSquare,
  CheckCircle2,
  Clock,
  User,
  Hash,
  AlertCircle,
} from "lucide-react";
import { DocumentRequest, documentTypeLabels } from "@/types";
import { Link } from "react-router-dom";
import { cn } from "@/lib/utils";
import { apiEndpoints } from "@/lib/api";

// Mock student database for validation
const MOCK_STUDENTS = [
  { email: "student@universite.ma", apogee: "12345678", cin: "AB123456" },
  { email: "etudiant@universite.ma", apogee: "87654321", cin: "CD987654" },
];

// Mock data for demonstration
const mockRequests: DocumentRequest[] = [
  {
    id: "1",
    requestNumber: "DMD-2024-0001",
    studentId: "123",
    documentType: "attestation_scolaire",
    status: "validee",
    createdAt: new Date("2024-11-15"),
    updatedAt: new Date("2024-11-16"),
    details: { annee: "2024-2025", niveau: "S3" },
    documentUrl: "#",
  },
  {
    id: "2",
    requestNumber: "DMD-2024-0002",
    studentId: "123",
    documentType: "releve_notes",
    status: "en_cours",
    createdAt: new Date("2024-11-20"),
    updatedAt: new Date("2024-11-21"),
    details: { semestre: "S2", annee: "2023-2024", typeReleve: "officiel" },
  },
  {
    id: "3",
    requestNumber: "DMD-2024-0003",
    studentId: "123",
    documentType: "convention_stage",
    status: "en_attente",
    createdAt: new Date("2024-11-28"),
    updatedAt: new Date("2024-11-28"),
    details: { entreprise: "Tech Corp", dateDebut: "2025-01-15", dateFin: "2025-03-15" },
  },
];

export default function Suivi() {
  // Identification state
  const [email, setEmail] = useState("");
  const [apogee, setApogee] = useState("");
  const [cin, setCin] = useState("");
  const [requestNumberSearch, setRequestNumberSearch] = useState("");
  const [isIdentificationValid, setIsIdentificationValid] = useState(false);
  const [identificationError, setIdentificationError] = useState("");
  
  const [requests, setRequests] = useState<DocumentRequest[] | null>(null);
  const [isLoading, setIsLoading] = useState(false);

  const validateIdentification = async () => {
    // Basic format validation
    if (!email || !apogee || !cin) {
      setIdentificationError("Veuillez remplir tous les champs");
      setIsIdentificationValid(false);
      return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      setIdentificationError("Adresse email invalide");
      setIsIdentificationValid(false);
      return;
    }
    
    if (!/^\d{6,10}$/.test(apogee)) {
      setIdentificationError("Numéro Apogée invalide (6-10 chiffres)");
      setIsIdentificationValid(false);
      return;
    }
    
    if (!/^[A-Z]{1,2}\d{4,8}$/i.test(cin)) {
      setIdentificationError("Numéro CIN invalide");
      setIsIdentificationValid(false);
      return;
    }

    setIsLoading(true);
    
    try {
      // Call real API
      const response = await apiEndpoints.suivreDemandes({
        email,
        apogee,
        cin,
      });

      if (response.data.success) {
        const demandes = response.data.data;
        
        // Filter by request number if provided
        let filteredRequests = demandes;
        if (requestNumberSearch.trim()) {
          filteredRequests = demandes.filter(
            (r: any) => r.num_demande.toLowerCase().includes(requestNumberSearch.toLowerCase())
          );
        }
        
        // Transform API data to frontend format
        const transformedRequests: DocumentRequest[] = filteredRequests.map((req: any) => ({
          id: req.id.toString(),
          requestNumber: req.num_demande,
          studentId: req.etudiant.apogee,
          documentType: req.type_document as any,
          status: req.status as any,
          createdAt: new Date(req.date_demande.split('/').reverse().join('-')),
          updatedAt: new Date(), // API doesn't provide this
          details: req.details,
          documentUrl: req.status === 'validee' ? '#' : undefined,
        }));
        
        setRequests(transformedRequests);
        setIsIdentificationValid(true);
        setIdentificationError("");
        toast.success(`${transformedRequests.length} demande(s) trouvée(s)`);
      } else {
        setIdentificationError(response.data.message || "Erreur lors de la recherche");
        setIsIdentificationValid(false);
      }
    } catch (error: any) {
      if (error.response?.status === 404) {
        setIdentificationError("Les informations fournies ne correspondent à aucun étudiant enregistré.");
        setIsIdentificationValid(false);
      } else {
        setIdentificationError("Erreur lors de la recherche de vos demandes");
        setIsIdentificationValid(false);
      }
    } finally {
      setIsLoading(false);
    }
  };

  const getStatusTimeline = (status: string) => {
    const steps = [
      { id: "submitted", label: "Soumise", completed: true },
      { id: "processing", label: "En traitement", completed: status !== "en_attente" },
      { id: "completed", label: "Terminée", completed: status === "validee" || status === "refusee" },
    ];
    return steps;
  };

  return (
    <Layout>
      <div className="min-h-[calc(100vh-4rem)] bg-gradient-to-b from-background to-accent/20 py-8 lg:py-12">
        <div className="container max-w-4xl">
          {/* Header */}
          <div className="text-center mb-8">
            <h1 className="text-3xl font-bold text-foreground mb-2">Suivi de demande</h1>
            <p className="text-muted-foreground">
              Consultez l'état de vos demandes en cours
            </p>
          </div>

          {/* Identification Card */}
          <div className={cn(
            "rounded-2xl border bg-card p-6 shadow-lg mb-8 transition-all",
            isIdentificationValid ? "border-green-500/50" : "border-border",
            !isIdentificationValid && identificationError ? "border-destructive/50" : ""
          )}>
            <div className="flex items-center gap-3 mb-4">
              <div className={cn(
                "flex h-10 w-10 items-center justify-center rounded-full",
                isIdentificationValid ? "bg-green-500 text-white" : "bg-primary text-primary-foreground"
              )}>
                {isIdentificationValid ? <CheckCircle2 className="h-5 w-5" /> : <User className="h-5 w-5" />}
              </div>
              <div>
                <h2 className="text-xl font-semibold text-foreground">Vérification d'identité</h2>
                <p className="text-sm text-muted-foreground">Entrez vos informations pour accéder à vos demandes</p>
              </div>
            </div>

            {!isIdentificationValid ? (
              <>
                <div className="grid gap-4 md:grid-cols-3">
                  <div className="space-y-2">
                    <Label htmlFor="email">Adresse email</Label>
                    <Input
                      id="email"
                      type="email"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      placeholder="votre.email@universite.ma"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="apogee">Numéro Apogée</Label>
                    <Input
                      id="apogee"
                      value={apogee}
                      onChange={(e) => setApogee(e.target.value)}
                      placeholder="Ex: 12345678"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="cin">Numéro CIN</Label>
                    <Input
                      id="cin"
                      value={cin}
                      onChange={(e) => setCin(e.target.value.toUpperCase())}
                      placeholder="Ex: AB123456"
                    />
                  </div>
                </div>

                {/* Request Number Search Field */}
                <div className="mt-4 p-4 bg-muted/30 rounded-xl">
                  <div className="space-y-2">
                    <Label htmlFor="requestNumberSearch" className="flex items-center gap-2">
                      <Hash className="h-4 w-4" />
                      Numéro de demande (optionnel)
                    </Label>
                    <Input
                      id="requestNumberSearch"
                      value={requestNumberSearch}
                      onChange={(e) => setRequestNumberSearch(e.target.value)}
                      placeholder="Ex: DMD-2024-0001"
                    />
                    <p className="text-xs text-muted-foreground">
                      Laissez vide pour voir toutes vos demandes, ou entrez un numéro pour filtrer
                    </p>
                  </div>
                </div>

                {identificationError && (
                  <div className="mt-4 flex items-center gap-2 text-destructive text-sm">
                    <AlertCircle className="h-4 w-4" />
                    {identificationError}
                  </div>
                )}

                <div className="mt-4">
                  <Button onClick={validateIdentification} disabled={isLoading} className="gap-2">
                    {isLoading ? (
                      "Vérification..."
                    ) : (
                      <>
                        <Search className="h-4 w-4" />
                        Rechercher mes demandes
                      </>
                    )}
                  </Button>
                </div>

                {/* Demo hint */}
                <div className="mt-4 p-3 bg-muted/50 rounded-lg">
                  <p className="text-xs text-muted-foreground">
                    <strong>Démo:</strong> Utilisez student@universite.ma / 12345678 / AB123456
                  </p>
                </div>
              </>
            ) : (
              <div className="flex items-center justify-between">
                <div className="text-sm text-muted-foreground">
                  <span className="font-medium text-foreground">{email}</span> • Apogée: {apogee} • CIN: {cin}
                </div>
                <Button 
                  variant="outline" 
                  size="sm" 
                  onClick={() => {
                    setIsIdentificationValid(false);
                    setRequests(null);
                    setRequestNumberSearch("");
                  }}
                >
                  Modifier
                </Button>
              </div>
            )}
          </div>

          {/* Results */}
          {requests && isIdentificationValid && (
            <div className="space-y-6 animate-fade-in">
              <h2 className="text-xl font-semibold text-foreground">
                Vos demandes ({requests.length})
              </h2>

              {requests.map((request) => (
                <div
                  key={request.id}
                  className="rounded-2xl border border-border bg-card p-6 shadow-sm hover:shadow-md transition-shadow"
                >
                  {/* Header */}
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div className="flex items-center gap-3">
                      <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <FileText className="h-6 w-6" />
                      </div>
                      <div>
                        <h3 className="font-semibold text-foreground">
                          {documentTypeLabels[request.documentType]}
                        </h3>
                        <div className="flex items-center gap-2 text-sm text-muted-foreground">
                          <Hash className="h-3.5 w-3.5" />
                          {request.requestNumber}
                        </div>
                      </div>
                    </div>
                    <StatusBadge status={request.status} />
                  </div>

                  {/* Timeline */}
                  <div className="mb-6">
                    <div className="flex items-center justify-between">
                      {getStatusTimeline(request.status).map((step, index) => (
                        <div key={step.id} className="flex items-center">
                          <div className="flex flex-col items-center">
                            <div
                              className={`flex h-8 w-8 items-center justify-center rounded-full ${
                                step.completed
                                  ? "bg-success text-success-foreground"
                                  : "bg-muted text-muted-foreground"
                              }`}
                            >
                              {step.completed ? (
                                <CheckCircle2 className="h-4 w-4" />
                              ) : (
                                <Clock className="h-4 w-4" />
                              )}
                            </div>
                            <span className="mt-2 text-xs font-medium text-muted-foreground">
                              {step.label}
                            </span>
                          </div>
                          {index < 2 && (
                            <div
                              className={`h-0.5 w-16 sm:w-24 lg:w-32 mx-2 ${
                                step.completed ? "bg-success" : "bg-muted"
                              }`}
                            />
                          )}
                        </div>
                      ))}
                    </div>
                  </div>

                  {/* Details */}
                  <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">
                    <div className="flex items-center gap-2 text-sm">
                      <Calendar className="h-4 w-4 text-muted-foreground" />
                      <span className="text-muted-foreground">Créée le:</span>
                      <span className="font-medium">
                        {request.createdAt.toLocaleDateString("fr-FR")}
                      </span>
                    </div>
                    <div className="flex items-center gap-2 text-sm">
                      <Clock className="h-4 w-4 text-muted-foreground" />
                      <span className="text-muted-foreground">Mise à jour:</span>
                      <span className="font-medium">
                        {request.updatedAt.toLocaleDateString("fr-FR")}
                      </span>
                    </div>
                  </div>

                  {/* Request Details */}
                  {Object.keys(request.details).length > 0 && (
                    <div className="rounded-lg bg-muted/50 p-4 mb-6">
                      <h4 className="text-sm font-semibold text-foreground mb-3">
                        Détails de la demande
                      </h4>
                      <div className="grid gap-2 sm:grid-cols-2 text-sm">
                        {Object.entries(request.details).map(([key, value]) => (
                          <div key={key} className="flex justify-between">
                            <span className="text-muted-foreground capitalize">
                              {key.replace(/([A-Z])/g, " $1").trim()}:
                            </span>
                            <span className="font-medium">{value}</span>
                          </div>
                        ))}
                      </div>
                    </div>
                  )}

                  {/* Actions */}
                  <div className="flex flex-wrap gap-3">
                    {request.status === "validee" && request.documentUrl && (
                      <Button variant="default" size="sm">
                        <Download className="mr-2 h-4 w-4" />
                        Télécharger le document
                      </Button>
                    )}
                    <Link to={`/reclamation?demande=${request.requestNumber}`}>
                      <Button variant="outline" size="sm">
                        <MessageSquare className="mr-2 h-4 w-4" />
                        Faire une réclamation
                      </Button>
                    </Link>
                  </div>
                </div>
              ))}
            </div>
          )}

          {/* Empty State */}
          {!requests && !isIdentificationValid && (
            <div className="text-center py-12">
              <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                <Search className="h-8 w-8 text-muted-foreground" />
              </div>
              <h3 className="text-lg font-semibold text-foreground mb-2">
                Vérifiez votre identité
              </h3>
              <p className="text-muted-foreground max-w-md mx-auto">
                Entrez vos informations personnelles (email, Apogée, CIN) pour consulter 
                l'état de vos demandes administratives.
              </p>
            </div>
          )}
        </div>
      </div>
    </Layout>
  );
}
