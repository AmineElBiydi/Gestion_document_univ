import { useState, useEffect } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import { Layout } from "@/components/layout/Layout";
import { Button } from "@/components/ui/button";
import { FileText, Search } from "lucide-react";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";
import { toast } from "sonner";
import {
  DocumentType,
  documentTypeLabels,
  ReclamationType,
  reclamationTypeLabels,
} from "@/types";
import {
  CheckCircle2,
  User,
  Send,
  AlertCircle,
  Edit3,
  Lock,
  MessageSquareWarning,
  Upload,
  X,
  FileText as FileIcon,
} from "lucide-react";
import { cn } from "@/lib/utils";
import { apiEndpoints } from "@/lib/api";

type FormAction = DocumentType | "reclamation";

export default function Demande() {
  const navigate = useNavigate();
  const location = useLocation();
  const [isLoading, setIsLoading] = useState(false);

  // Identification data
  const [email, setEmail] = useState("");
  const [apogee, setApogee] = useState("");
  const [cin, setCin] = useState("");

  // Validation state
  const [isIdentificationValid, setIsIdentificationValid] = useState(false);
  const [isIdentificationLocked, setIsIdentificationLocked] = useState(false);
  const [identificationError, setIdentificationError] = useState("");
  const [fieldValidation, setFieldValidation] = useState({
    email: false,
    apogee: false,
    cin: false
  });

  // Document/Action data
  const [selectedAction, setSelectedAction] = useState<FormAction | "">("");
  const [details, setDetails] = useState<Record<string, string>>({});
  const [studentAnneesUniversitaires, setStudentAnneesUniversitaires] = useState<{ id: number, libelle: string, est_active: boolean }[]>([]);

  // Reclamation data
  const [requestNumber, setRequestNumber] = useState("");
  const [reclamationType, setReclamationType] = useState<ReclamationType | "">("");
  const [reclamationDescription, setReclamationDescription] = useState("");
  const [attachment, setAttachment] = useState<File | null>(null);
  const [hasFilledDocumentDetails, setHasFilledDocumentDetails] = useState(false);

  // Real-time validation when identification changes
  useEffect(() => {
    if (email || apogee || cin) {
      validateIdentificationRealtime();
    } else {
      // Reset validation when all fields are empty
      setIsIdentificationValid(false);
      setIsIdentificationLocked(false);
      setIdentificationError("");
      setFieldValidation({ email: false, apogee: false, cin: false });
      // Reset document data when identification is cleared
      if (hasFilledDocumentDetails) {
        setSelectedAction("");
        setDetails({});
        setHasFilledDocumentDetails(false);
      }
    }
  }, [email, apogee, cin]);

  const validateIdentificationRealtime = async () => {
    // Only validate if we have some input
    if (!email && !apogee && !cin) return;

    try {
      const response = await apiEndpoints.validateStudent({
        email: email || "",
        apogee: apogee || "",
        cin: cin || ""
      });

      const validation = response.data;
      setFieldValidation({
        email: validation.email_valid,
        apogee: validation.apogee_valid,
        cin: validation.cin_valid
      });

      // Only unlock if all three fields match the SAME student
      if (validation.all_valid) {
        setIsIdentificationValid(true);
        setIsIdentificationLocked(true);
        setIdentificationError("");
        // Récupérer les années universitaires de l'étudiant
        setStudentAnneesUniversitaires(validation.annees_universitaires || []);
      } else {
        setIsIdentificationValid(false);
        setIsIdentificationLocked(false);

        // Set appropriate error message based on error type
        switch (validation.error_type) {
          case 'pattern':
            setIdentificationError("Format incorrect. Vérifiez email (xxx@xxx.xx), apogée (6-10 chiffres), CIN (ex: AB123456).");
            break;
          case 'existence':
            setIdentificationError("Informations introuvables. Vérifiez vos données.");
            break;
          case 'relationship':
            setIdentificationError("Les informations ne correspondent pas au même étudiant.");
            break;
          default:
            setIdentificationError("Informations incomplètes. Veuillez remplir tous les champs.");
        }
      }
    } catch (error) {
      setIsIdentificationValid(false);
      setIsIdentificationLocked(false);
      setFieldValidation({ email: false, apogee: false, cin: false });
    }
  };

  const handleModifyIdentification = () => {
    setIsIdentificationLocked(false);
    setIsIdentificationValid(false);
    setIdentificationError("");
    setFieldValidation({ email: false, apogee: false, cin: false });
    setStudentAnneesUniversitaires([]);
    // Reset document data when identification is modified after filling
    if (hasFilledDocumentDetails) {
      setSelectedAction("");
      setDetails({});
      setHasFilledDocumentDetails(false);
    }
  };

  const handleActionChange = (value: FormAction) => {
    setSelectedAction(value);
    setDetails({});
    setHasFilledDocumentDetails(false);
    // Reset reclamation fields when switching
    if (value !== "reclamation") {
      setRequestNumber("");
      setReclamationType("");
      setReclamationDescription("");
      setAttachment(null);
    }
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      if (file.size > 5 * 1024 * 1024) {
        toast.error("Le fichier ne doit pas dépasser 5 Mo");
        return;
      }
      setAttachment(file);
      setHasFilledDocumentDetails(true);
    }
  };

  const removeAttachment = () => {
    setAttachment(null);
  };


  const isFormComplete = () => {
    if (!isIdentificationValid || !selectedAction) return false;

    // Check required details based on document type
    switch (selectedAction) {
      case "attestation_scolaire":
        return true;
      case "attestation_reussite":
        return details.annee;
      case "releve_notes":
        return details.annee;
      case "convention_stage":
        return details.entreprise && details.adresse && details.dateDebut && details.dateFin && details.encadrant && details.sujet;
      case "reclamation":
        return requestNumber && reclamationType && reclamationDescription;
      default:
        return false;
    }
  };

  const handleSubmit = async () => {
    if (!isIdentificationValid) {
      toast.error("Veuillez vérifier vos informations d'identification");
      return;
    }

    setIsLoading(true);

    try {
      if (selectedAction === "reclamation") {
        // Create FormData for file upload
        const formData = new FormData();
        formData.append('num_demande', requestNumber);
        formData.append('type', reclamationType);
        formData.append('description', reclamationDescription);

        if (attachment) {
          formData.append('piece_jointe', attachment);
        }

        const response = await apiEndpoints.createReclamation(formData);

        if (response.data.success) {
          toast.success("Réclamation envoyée avec succès !");
          navigate("/suivi");
        } else {
          toast.error(response.data.message || "Erreur lors de la soumission");
        }
      } else {
        // Prepare data for API
        const requestData: any = {
          email,
          apogee,
          cin,
          type_document: selectedAction,
          inscription_id: studentAnneesUniversitaires.find(a => a.libelle === details.annee)?.id
        };

        // Add document-specific details
        switch (selectedAction) {
          case "attestation_scolaire":
            requestData.niveau = details.niveau;
            requestData.filiere = "Informatique"; // Default value
            requestData.annee_universitaire = details.annee;
            break;
          case "attestation_reussite":
            requestData.filiere = "Informatique"; // Default value
            requestData.annee_universitaire = details.annee;
            requestData.cycle = "Licence"; // Default value
            requestData.session = "Normale"; // Default value
            requestData.type_releve = "officiel"; // Default value
            break;
          case "releve_notes":
            requestData.annee_universitaire = details.annee;
            break;
          case "convention_stage":
            requestData.date_debut = details.dateDebut;
            requestData.date_fin = details.dateFin;
            requestData.entreprise = details.entreprise;
            requestData.adresse_entreprise = details.adresse;
            requestData.email_encadrant = "encadrant@entreprise.com"; // Default value
            requestData.telephone_encadrant = "0600000000"; // Default value
            requestData.encadrant_entreprise = details.encadrant;
            requestData.encadrant_pedagogique = "Prof. Université"; // Default value
            requestData.fonction_encadrant = "Encadrant technique"; // Default value
            requestData.sujet = details.sujet;
            break;
        }

        const response = await apiEndpoints.createDemande(requestData);

        if (response.data.success) {
          const newRequestNumber = response.data.data.num_demande;
          toast.success(`Demande enregistrée avec succès ! Numéro: ${newRequestNumber}`);
          navigate("/suivi", { state: { requestNumber: newRequestNumber } });
        } else {
          toast.error(response.data.message || "Erreur lors de la soumission");
        }
      }
    } catch (error: any) {
      if (error.response?.status === 404) {
        setIdentificationError("Les informations fournies ne correspondent à aucun étudiant enregistré.");
        setIsIdentificationValid(false);
        setIsIdentificationLocked(false);
      } else {
        toast.error(error.response?.data?.message || "Erreur lors de la soumission");
      }
    } finally {
      setIsLoading(false);
    }
  };

  const renderReclamationFields = () => (
    <>
      {/* Request Number */}
      <div className="space-y-2">
        <Label htmlFor="requestNumber">
          Numéro de demande concernée <span className="text-destructive">*</span>
        </Label>
        <Input
          id="requestNumber"
          value={requestNumber}
          onChange={(e) => {
            setRequestNumber(e.target.value);
            setHasFilledDocumentDetails(true);
          }}
          placeholder="Ex: DMD-2024-0001"
        />
      </div>

      {/* Reclamation Type */}
      <div className="space-y-2">
        <Label htmlFor="reclamationType">
          Objet de la réclamation <span className="text-destructive">*</span>
        </Label>
        <Select
          value={reclamationType}
          onValueChange={(v) => {
            setReclamationType(v as ReclamationType);
            setHasFilledDocumentDetails(true);
          }}
        >
          <SelectTrigger>
            <SelectValue placeholder="Sélectionnez un objet" />
          </SelectTrigger>
          <SelectContent>
            {(Object.keys(reclamationTypeLabels) as ReclamationType[]).map(
              (type) => (
                <SelectItem key={type} value={type}>
                  {reclamationTypeLabels[type]}
                </SelectItem>
              )
            )}
          </SelectContent>
        </Select>
      </div>

      {/* Description */}
      <div className="space-y-2">
        <Label htmlFor="description">
          Description détaillée <span className="text-destructive">*</span>
        </Label>
        <Textarea
          id="description"
          value={reclamationDescription}
          onChange={(e) => {
            setReclamationDescription(e.target.value);
            setHasFilledDocumentDetails(true);
          }}
          placeholder="Décrivez votre problème en détail..."
          rows={5}
          className="resize-none"
        />
      </div>

      {/* File Upload */}
      <div className="space-y-2">
        <Label>Pièce jointe (optionnel)</Label>
        {!attachment ? (
          <label className="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-border rounded-xl cursor-pointer hover:border-primary/50 hover:bg-accent/50 transition-all">
            <div className="flex flex-col items-center justify-center pt-5 pb-6">
              <Upload className="h-8 w-8 text-muted-foreground mb-2" />
              <p className="text-sm text-muted-foreground">
                <span className="font-semibold text-primary">Cliquez pour ajouter</span>
                {" "}ou glissez-déposez
              </p>
              <p className="text-xs text-muted-foreground mt-1">
                PNG, JPG, PDF (max 5 Mo)
              </p>
            </div>
            <input
              type="file"
              className="hidden"
              accept=".png,.jpg,.jpeg,.pdf"
              onChange={handleFileChange}
            />
          </label>
        ) : (
          <div className="flex items-center justify-between p-4 rounded-xl bg-muted/50 border border-border">
            <div className="flex items-center gap-3">
              <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                <FileIcon className="h-5 w-5 text-primary" />
              </div>
              <div>
                <p className="text-sm font-medium text-foreground truncate max-w-[200px]">
                  {attachment.name}
                </p>
                <p className="text-xs text-muted-foreground">
                  {(attachment.size / 1024).toFixed(1)} Ko
                </p>
              </div>
            </div>
            <Button
              type="button"
              variant="ghost"
              size="icon"
              onClick={removeAttachment}
            >
              <X className="h-4 w-4" />
            </Button>
          </div>
        )}
      </div>
    </>
  );

  const renderDetailsFields = () => {
    if (!selectedAction) return null;

    if (selectedAction === "reclamation") {
      return renderReclamationFields();
    }

    const fields = {
      attestation_scolarite: (
        <>
          <div className="space-y-2">
            <Label htmlFor="annee">Année universitaire *</Label>
            <Select
              value={details.annee || ""}
              onValueChange={(v) => {
                setDetails({ ...details, annee: v });
                setHasFilledDocumentDetails(true);
              }}
            >
              <SelectTrigger>
                <SelectValue placeholder="Sélectionnez l'année" />
              </SelectTrigger>
              <SelectContent>
                {studentAnneesUniversitaires.map((item) => (
                  <SelectItem key={item.id} value={item.libelle}>
                    {item.libelle}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
          <div className="space-y-2">
            <Label htmlFor="niveau">Niveau / Semestre *</Label>
            <Select
              value={details.niveau || ""}
              onValueChange={(v) => {
                setDetails({ ...details, niveau: v });
                setHasFilledDocumentDetails(true);
              }}
            >
              <SelectTrigger>
                <SelectValue placeholder="Sélectionnez le niveau" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="S1">Semestre 1</SelectItem>
                <SelectItem value="S2">Semestre 2</SelectItem>
                <SelectItem value="S3">Semestre 3</SelectItem>
                <SelectItem value="S4">Semestre 4</SelectItem>
                <SelectItem value="S5">Semestre 5</SelectItem>
                <SelectItem value="S6">Semestre 6</SelectItem>
              </SelectContent>
            </Select>
          </div>
        </>
      ),
      attestation_reussite: (
        <>
          <div className="space-y-2">
            <Label htmlFor="annee">Dernière année validée *</Label>
            <Select
              value={details.annee || ""}
              onValueChange={(v) => {
                setDetails({ ...details, annee: v });
                setHasFilledDocumentDetails(true);
              }}
            >
              <SelectTrigger>
                <SelectValue placeholder="Sélectionnez l'année" />
              </SelectTrigger>
              <SelectContent>
                {studentAnneesUniversitaires
                  .filter((item) => !item.est_active)
                  .map((item) => (
                    <SelectItem key={item.id} value={item.libelle}>
                      {item.libelle}
                    </SelectItem>
                  ))}
              </SelectContent>
            </Select>
          </div>
        </>
      ),
      releve_notes: (
        <>

          <div className="space-y-2">
            <Label htmlFor="annee">Année universitaire *</Label>
            <Select
              value={details.annee || ""}
              onValueChange={(v) => {
                setDetails({ ...details, annee: v });
                setHasFilledDocumentDetails(true);
              }}
            >
              <SelectTrigger>
                <SelectValue placeholder="Sélectionnez l'année" />
              </SelectTrigger>
              <SelectContent>
                {studentAnneesUniversitaires
                  .filter(item => !item.est_active)
                  .map((item) => (
                    <SelectItem key={item.id} value={item.libelle}>
                      {item.libelle}
                    </SelectItem>
                  ))}
              </SelectContent>
            </Select>
          </div>

        </>
      ),
      convention_stage: (
        <>
          <div className="space-y-2">
            <Label htmlFor="entreprise">Entreprise d'accueil *</Label>
            <Input
              id="entreprise"
              value={details.entreprise || ""}
              onChange={(e) => {
                setDetails({ ...details, entreprise: e.target.value });
                setHasFilledDocumentDetails(true);
              }}
              placeholder="Nom de l'entreprise"
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="adresse">Adresse de l'entreprise *</Label>
            <Textarea
              id="adresse"
              value={details.adresse || ""}
              onChange={(e) => {
                setDetails({ ...details, adresse: e.target.value });
                setHasFilledDocumentDetails(true);
              }}
              placeholder="Adresse complète"
              rows={2}
            />
          </div>
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="dateDebut">Date de début *</Label>
              <Input
                id="dateDebut"
                type="date"
                value={details.dateDebut || ""}
                onChange={(e) => {
                  setDetails({ ...details, dateDebut: e.target.value });
                  setHasFilledDocumentDetails(true);
                }}
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="dateFin">Date de fin *</Label>
              <Input
                id="dateFin"
                type="date"
                value={details.dateFin || ""}
                onChange={(e) => {
                  setDetails({ ...details, dateFin: e.target.value });
                  setHasFilledDocumentDetails(true);
                }}
              />
            </div>
          </div>
          <div className="space-y-2">
            <Label htmlFor="encadrant">Nom de l'encadrant académique *</Label>
            <Input
              id="encadrant"
              value={details.encadrant || ""}
              onChange={(e) => {
                setDetails({ ...details, encadrant: e.target.value });
                setHasFilledDocumentDetails(true);
              }}
              placeholder="Nom et prénom"
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor="sujet">Sujet du stage *</Label>
            <Textarea
              id="sujet"
              value={details.sujet || ""}
              onChange={(e) => {
                setDetails({ ...details, sujet: e.target.value });
                setHasFilledDocumentDetails(true);
              }}
              placeholder="Décrivez brièvement le sujet du stage"
              rows={3}
            />
          </div>
        </>
      ),
    };

    return fields[selectedAction as DocumentType] || null;
  };

  return (
    <Layout>
      <div className="min-h-[calc(100vh-4rem)] bg-gradient-to-b from-background to-accent/20 py-8 lg:py-12">
        <div className="container max-w-4xl">
          <div className="mb-8 text-center">
            <h1 className="text-3xl font-bold text-foreground">Nouvelle Demande</h1>
            <p className="text-muted-foreground mt-2">
              Remplissez le formulaire ci-dessous pour soumettre votre demande
            </p>
          </div>

          <div className="space-y-6">
            {/* Section 1: Identification */}
            <div className={cn(
              "rounded-2xl border bg-card p-6 shadow-lg transition-all",
              isIdentificationValid ? "border-green-500/50" : "border-border",
              !isIdentificationValid && identificationError ? "border-destructive/50" : ""
            )}>
              <div className="flex items-center justify-between mb-4">
                <div className="flex items-center gap-3">
                  <div className={cn(
                    "flex h-10 w-10 items-center justify-center rounded-full",
                    isIdentificationValid ? "bg-green-500 text-white" : "bg-primary text-primary-foreground"
                  )}>
                    {isIdentificationValid ? <CheckCircle2 className="h-5 w-5" /> : <User className="h-5 w-5" />}
                  </div>
                  <div>
                    <h2 className="text-xl font-semibold text-foreground">Identification</h2>
                    <p className="text-sm text-muted-foreground">Vérifiez votre identité avant de continuer</p>
                  </div>
                </div>
                {isIdentificationLocked && (
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={handleModifyIdentification}
                    className="gap-2"
                  >
                    <Edit3 className="h-4 w-4" />
                    Modifier
                  </Button>
                )}
              </div>

              <div className="grid gap-4 md:grid-cols-3">
                <div className="space-y-2">
                  <Label htmlFor="email" className={cn(
                    "flex items-center gap-2",
                    fieldValidation.email ? "text-green-600" : ""
                  )}>
                    Adresse email
                    {fieldValidation.email && <CheckCircle2 className="h-4 w-4" />}
                  </Label>
                  <Input
                    id="email"
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    placeholder="votre.email@universite.ma"
                    disabled={isIdentificationLocked}
                    className={cn(
                      isIdentificationLocked ? "opacity-70" : "",
                      fieldValidation.email && "border-green-500 focus:border-green-500"
                    )}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="apogee" className={cn(
                    "flex items-center gap-2",
                    fieldValidation.apogee ? "text-green-600" : ""
                  )}>
                    Numéro Apogée
                    {fieldValidation.apogee && <CheckCircle2 className="h-4 w-4" />}
                  </Label>
                  <Input
                    id="apogee"
                    value={apogee}
                    onChange={(e) => setApogee(e.target.value)}
                    placeholder="Ex: 12345678"
                    disabled={isIdentificationLocked}
                    className={cn(
                      isIdentificationLocked ? "opacity-70" : "",
                      fieldValidation.apogee && "border-green-500 focus:border-green-500"
                    )}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="cin" className={cn(
                    "flex items-center gap-2",
                    fieldValidation.cin ? "text-green-600" : ""
                  )}>
                    Numéro CIN
                    {fieldValidation.cin && <CheckCircle2 className="h-4 w-4" />}
                  </Label>
                  <Input
                    id="cin"
                    value={cin}
                    onChange={(e) => setCin(e.target.value.toUpperCase())}
                    placeholder="Ex: AB123456"
                    disabled={isIdentificationLocked}
                    className={cn(
                      isIdentificationLocked ? "opacity-70" : "",
                      fieldValidation.cin && "border-green-500 focus:border-green-500"
                    )}
                  />
                </div>
              </div>

              {identificationError && (
                <div className="mt-4 flex items-center gap-2 text-destructive text-sm">
                  <AlertCircle className="h-4 w-4" />
                  {identificationError}
                </div>
              )}

            </div>

            {/* Section 2: Document Selection & Details */}
            <div className={cn(
              "rounded-2xl border bg-card p-6 shadow-lg transition-all",
              !isIdentificationValid && "opacity-50 pointer-events-none",
              hasFilledDocumentDetails && isIdentificationLocked && !isIdentificationValid && "border-destructive/50"
            )}>
              <div className="flex items-center gap-3 mb-4">
                <div className={cn(
                  "flex h-10 w-10 items-center justify-center rounded-full",
                  isFormComplete() ? "bg-green-500 text-white" : "bg-muted text-muted-foreground"
                )}>
                  {isFormComplete() ? <CheckCircle2 className="h-5 w-5" /> : <FileText className="h-5 w-5" />}
                </div>
                <div>
                  <h2 className="text-xl font-semibold text-foreground">Document demandé</h2>
                  <p className="text-sm text-muted-foreground">Sélectionnez le type de document et remplissez les détails</p>
                </div>
              </div>

              {/* Document locked warning */}
              {hasFilledDocumentDetails && !isIdentificationValid && (
                <div className="mb-4 p-3 bg-destructive/10 border border-destructive/30 rounded-lg flex items-center gap-2 text-destructive text-sm">
                  <Lock className="h-4 w-4" />
                  Les informations du document sont verrouillées. Validez d'abord votre identité.
                </div>
              )}

              <div className="space-y-6">
                {/* Document Type Dropdown */}
                <div className="space-y-2">
                  <Label htmlFor="documentType">Type de demande *</Label>
                  <Select
                    value={selectedAction}
                    onValueChange={(v) => handleActionChange(v as FormAction)}
                    disabled={hasFilledDocumentDetails && !isIdentificationValid}
                  >
                    <SelectTrigger className="w-full">
                      <SelectValue placeholder="Sélectionnez un type de demande" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="attestation_scolaire">{documentTypeLabels.attestation_scolaire}</SelectItem>
                      <SelectItem value="attestation_reussite">{documentTypeLabels.attestation_reussite}</SelectItem>
                      <SelectItem value="releve_notes">{documentTypeLabels.releve_notes}</SelectItem>
                      <SelectItem value="convention_stage">{documentTypeLabels.convention_stage}</SelectItem>
                      <SelectItem value="reclamation">
                        <span className="flex items-center gap-2">
                          <MessageSquareWarning className="h-4 w-4" />
                          Passer une réclamation
                        </span>
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                {/* Document Details */}
                {selectedAction && (
                  <div className={cn(
                    "space-y-4 p-4 bg-muted/30 rounded-xl",
                    hasFilledDocumentDetails && !isIdentificationValid && "opacity-50 pointer-events-none"
                  )}>
                    <h3 className="font-medium text-foreground">
                      {selectedAction === "reclamation" ? "Détails de la réclamation" : "Informations complémentaires"}
                    </h3>
                    {renderDetailsFields()}
                  </div>
                )}
              </div>
            </div>

            {/* Submit Button */}
            <div className="flex justify-end">
              <Button
                onClick={handleSubmit}
                disabled={isLoading || !isFormComplete() || !isIdentificationValid}
                variant="success"
                className="gap-2 w-full sm:w-auto"
              >
                {isLoading ? (
                  "Envoi en cours..."
                ) : (
                  <>
                    <Send className="h-4 w-4" />
                    Soumettre la demande
                  </>
                )}
              </Button>
            </div>
          </div>
        </div>
      </div>
    </Layout>
  );
}
