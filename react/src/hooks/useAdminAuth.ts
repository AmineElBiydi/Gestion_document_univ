import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

export function useAdminAuth() {
  const navigate = useNavigate();
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const adminLoggedIn = localStorage.getItem("adminLoggedIn");
    if (adminLoggedIn === "true") {
      setIsAuthenticated(true);
    } else {
      navigate("/admin/login");
    }
    setIsLoading(false);
  }, [navigate]);

  const logout = () => {
    localStorage.removeItem("adminLoggedIn");
    navigate("/admin/login");
  };

  return { isAuthenticated, isLoading, logout };
}
